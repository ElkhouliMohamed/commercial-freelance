<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Contact;
use App\Models\Plan;
use App\Models\Rdv;
use App\Models\User;
use App\Models\Abonnement;
use App\Models\Commission;
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
            $devis = Devis::with(['rdv', 'contact', 'freelancer', 'plans'])
                ->where('freelancer_id', auth()->id())
                ->paginate(10);
        } else {
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
        $plans = Plan::all();

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

        $rdv = Rdv::with('freelancer')->findOrFail($validated['rdv_id']);
        $freelancerId = $validated['freelancer_id'] ?? $rdv->freelancer_id;

        if (!$freelancerId) {
            abort(400, 'No freelancer assigned to this RDV or in the request.');
        }

        $freelancer = User::findOrFail($freelancerId);
        if (!$freelancer->hasRole('Freelancer')) {
            abort(403, 'Le freelancer sélectionné n\'est pas valide.');
        }

        $freelancerDevisCount = $freelancer->devis()->count();
        $commissionAmount = 0;

        if ($freelancerDevisCount <= 10) {
            $commissionAmount = 500;
        } elseif ($freelancerDevisCount <= 20) {
            $commissionAmount = 1000;
        } elseif ($freelancerDevisCount <= 30) {
            $commissionAmount = 1500;
        } else {
            $commissionAmount = 2000;
        }

        $commissionRate = $validated['commission_rate'] ?? 20;

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

        if ($validated['statut'] === 'Accepté') {
            $this->handleAcceptedStatus($devis);
        }

        return redirect()->route('devis.index')->with('success', 'Devis créé avec succès.');
    }

    /**
     * Update the specified devis in storage and handle commission if status changes to "Accepté".
     */
    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'freelancer_id' => 'nullable|exists:users,id',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|string|in:Brouillon,En Attente,Accepté,Refusé,Annulé',
            'date_validite' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'plans' => 'required|array',
            'plans.*' => 'exists:plans,id',
        ]);

        $oldStatus = $devis->statut;

        $devis->update([
            'freelancer_id' => $validated['freelancer_id'],
            'montant' => $validated['montant'],
            'statut' => $validated['statut'],
            'date_validite' => $validated['date_validite'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $devis->plans()->sync($validated['plans']);

        if ($validated['statut'] === 'Accepté' && $oldStatus !== 'Accepté') {
            $this->handleAcceptedStatus($devis);
        } else {
            $this->updateCommission($devis);
        }

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

            $devis->delete();

            Log::info("Devis soft deleted successfully.");

            return redirect()->route('devis.index')->with('success', 'Devis supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Error deleting devis: " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur s’est produite lors de la suppression.');
        }
    }

    /**
     * Show the form for editing the specified devis.
     */
    public function edit(Devis $devis)
    {
        $freelancers = User::role('Freelancer')->get();
        $plans = Plan::all();

        return view('devis.edit', compact('devis', 'freelancers', 'plans'));
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

        $abonnement = Abonnement::find($devis->abonnement_id);
        if ($abonnement) {
            $abonnement->increment('contracts_count');
        }

        if ($devis->statut === 'validé') {
            $this->handleAcceptedStatus($devis);
        }

        return redirect()->route('devis.index')->with('success', 'Devis validé avec succès.');
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
     * Update the commission for the specified devis.
     */
    public function updateCommission(Devis $devis)
    {
        $freelancer = $devis->freelancer;

        if ($freelancer && $devis->montant) {
            $commissionRate = $devis->commission_rate ?? 20;
            $commissionAmount = ($devis->montant * $commissionRate) / 100;
            $devis->update(['commission' => $commissionAmount]);

            Log::info('Commission updated for Devis:', [
                'devis_id' => $devis->id,
                'freelancer_id' => $freelancer->id,
                'commission' => $commissionAmount,
            ]);
        }
    }

    /**
     * Handle logic when devis status is set to "Accepté" or "validé".
     */
    private function handleAcceptedStatus(Devis $devis)
    {
        if (!$devis->freelancer_id) {
            Log::warning('No freelancer assigned to devis ID: ' . $devis->id);
            return;
        }

        $commissionRate = $devis->commission_rate ?? 20;
        $commissionAmount = ($devis->montant * $commissionRate) / 100;

        $commission = Commission::updateOrCreate(
            ['devis_id' => $devis->id],
            [
                'freelancer_id' => $devis->freelancer_id,
                'montant' => $commissionAmount,
                'description' => 'Commission for accepted devis #' . $devis->id,
                'statut' => 'En Attente',
                'demande_paiement' => false,
                'level' => 'standard',
            ]
        );

        Log::info('Commission created/updated for Devis:', [
            'devis_id' => $devis->id,
            'freelancer_id' => $devis->freelancer_id,
            'commission_amount' => $commissionAmount,
        ]);

        if ($devis->rdv) {
            $devis->rdv->update(['statut' => 'confirmé']);
        }
    }
}
