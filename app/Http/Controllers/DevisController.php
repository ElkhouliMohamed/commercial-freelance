<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Contact;
use App\Models\Rdv;
use App\Models\User;
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

        $devis = Devis::with(['rdv', 'contact', 'freelancer', 'service'])->paginate(10);

        return view('devis.index', compact('devis'));
    }

    /**
     * Show the form for creating a new devis.
     */
    public function create($rdvId)
    {
        $rdv = Rdv::with(['contact', 'freelancer'])->findOrFail($rdvId);
        $freelancers = User::role('Freelancer')->get();
        $services = Service::all(); // Assuming you have a Service model

        return view('devis.create', compact('rdv', 'freelancers', 'services'));
    }

    /**
     * Store a newly created devis in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rdv_id' => 'required|exists:rdvs,id',
            'contact_id' => 'required|exists:contacts,id',
            'freelancer_id' => 'nullable|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|string|in:Brouillon,En Attente,Accepté,Refusé,Annulé',
            'date_validite' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        Devis::create($validated);

        return redirect()->route('devis.index')->with('success', 'Devis créé avec succès.');
    }

    /**
     * Show the form for editing the specified devis.
     */
    public function edit(Devis $devis)
    {
        $freelancers = User::role('Freelancer')->get();
        $services = Service::all();

        return view('devis.edit', compact('devis', 'freelancers', 'services'));
    }

    /**
     * Update the specified devis in storage.
     */
    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'freelancer_id' => 'nullable|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|string|in:Brouillon,En Attente,Accepté,Refusé,Annulé',
            'date_validite' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $devis->update($validated);

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
}
