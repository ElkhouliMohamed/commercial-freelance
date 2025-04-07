<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Contact;
use App\Models\Plan;
use App\Models\Rdv;
use App\Models\User;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DevisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the devis.
     */
    public function index()
    {
        $this->authorizeRole(['Freelancer', 'Admin', 'Account Manager']);

        if (auth()->user()->hasRole('Freelancer')) {
            // Freelancers can only see Devis assigned to them
            $devis = Devis::with(['rdv', 'contact', 'freelancer', 'plans'])
                ->where('freelancer_id', auth()->id())
                ->paginate(10);
        } else {
            // Admins and Account Managers can see all Devis
            $devis = Devis::with(['rdv', 'contact', 'freelancer', 'plans'])->paginate(10);
        }

        return view('devis.index', compact('devis'));
    }

    /**
     * Show the form for creating a new devis.
     */
    public function create($rdvId)
    {
        $rdv = Rdv::with(['contact', 'freelancer'])->findOrFail($rdvId);
        $freelancers = User::role('Freelancer')->get();
        $plans = Plan::all(); // Fetch all plans

        return view('devis.create', compact('rdv', 'freelancers', 'plans'));
    }

    /**
     * Store a newly created devis in storage.
     */
    public function store(Request $request)
    {
        Log::info('Storing Devis with data:', $request->all());

        $validated = $request->validate([
            'rdv_id' => 'required|exists:rdvs,id',
            'contact_id' => 'required|exists:contacts,id',
            'freelancer_id' => 'nullable|exists:users,id',
            'plans' => 'required|array',
            'plans.*' => 'exists:plans,id',
            'montant' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'statut' => 'required|string|in:Brouillon,En Attente,Accepté,Refusé,Annulé',
            'date_validite' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (Devis::where('rdv_id', $validated['rdv_id'])->exists()) {
            return back()->with('error', 'Un devis existe déjà pour ce rendez-vous.');
        }

        $rdv = Rdv::with('freelancer')->find($validated['rdv_id']);
        $freelancerId = $validated['freelancer_id'] ?? $rdv->freelancer_id;

        $freelancer = User::find($freelancerId);
        if (!$freelancer || !$freelancer->hasRole('Freelancer')) {
            abort(403, 'Le freelancer sélectionné n\'est pas valide.');
        }

        $commissionRate = $validated['commission_rate'] ?? 20; // Taux par défaut : 20%
        $commissionAmount = ($validated['montant'] * $commissionRate) / 100;

        $devis = Devis::create([
            'rdv_id' => $validated['rdv_id'],
            'contact_id' => $validated['contact_id'],
            'freelancer_id' => $freelancerId,
            'montant' => $validated['montant'],
            'commission_rate' => $commissionRate,
            'commission' => $commissionAmount,
            'statut' => $validated['statut'],
            'date_validite' => $validated['date_validite'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $devis->plans()->attach($validated['plans']);

        return redirect()->route('devis.index')->with('success', 'Devis créé avec succès.');
    }



    /**
     * Show the form for editing the specified devis.
     */
    public function edit(Devis $devis)
    {
        $freelancers = User::role('Freelancer')->get();
        $plans = Plan::all(); // Fetch all plans

        return view('devis.edit', compact('devis', 'freelancers', 'plans'));
    }

    /**
     * Update the specified devis in storage.
     */
    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'freelancer_id' => 'nullable|exists:users,id',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|string|in:Brouillon,En Attente,Accepté,Refusé,Annulé',
            'date_validite' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'plans' => 'required|array', // Add plans to validation
            'plans.*' => 'exists:plans,id', // Validate plan IDs
        ]);

        $devis->update([
            'freelancer_id' => $validated['freelancer_id'],
            'montant' => $validated['montant'],
            'statut' => $validated['statut'],
            'date_validite' => $validated['date_validite'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Sync the plans (replace existing plans with new ones)
        $devis->plans()->sync($validated['plans']);

        return redirect()->route('devis.index')->with('success', 'Devis mis à jour avec succès.');
    }

    /**
     * Remove the specified devis from storage (soft delete).
     */
    public function destroy(Devis $devis)
    {
        try {
            Gate::authorize('delete-devis', $devis);

            Log::info("Deleting devis with ID: " . $devis->id);

            $devis->delete(); // Soft delete instead of forceDelete

            Log::info("Devis soft deleted successfully.");

            return redirect()->route('devis.index')->with('success', 'Devis supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Error deleting devis: " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur s’est produite lors de la suppression.');
        }
    }

    /**
     * Authorize the user based on roles.
     */
    private function authorizeRole(array $roles)
    {
        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Display the specified devis.
     */
    public function show(Devis $devis)
    {
        return view('devis.show', compact('devis'));
    }

    /**
     * Validate the specified devis.
     */
    public function validateDevis(Devis $devis)
    {
        $devis->update(['statut' => 'validé']);

        // Increment contracts_count for the associated abonnement
        $abonnement = Abonnement::find($devis->abonnement_id);
        if ($abonnement) {
            $abonnement->increment('contracts_count');
        }

        return redirect()->route('devis.index')->with('success', 'Devis validé avec succès.');
    }
}
