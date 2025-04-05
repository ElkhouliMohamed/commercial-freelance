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
        // Apply middleware to ensure only authenticated users can access these routes
        $this->middleware('auth');
    }

    /**
     * Display a listing of the devis.
     */
    public function index()
    {
        // Check if the user has the required role
        $this->authorizeRole(['Freelancer', 'Admin','Account Manager']);

        // Retrieve all devis with related RDV, Contact, and Freelancer data
        $devis = Devis::with(['rdv', 'contact', 'freelancer'])->get();

        return view('devis.index', compact('devis'));
    }

    /**
     * Show the form for creating a new devis.
     */
    public function create($rdvId)
    {
        // Fetch the RDV details
        $rdv = Rdv::with(['contact', 'freelancer'])->findOrFail($rdvId);

        // Fetch all freelancers
        $freelancers = User::role('Freelancer')->get(); // Assuming Spatie roles are used

        return view('devis.create', compact('rdv', 'freelancers'));
    }

    /**
     * Store a newly created devis in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rdv_id' => 'required|exists:rdvs,id',
            'contact_id' => 'required|exists:contacts,id',
            'freelance_id' => 'nullable|exists:users,id',
            'montant' => 'required|numeric',
            'statut' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Devis::create($validated);

        return redirect()->route('devis.index')->with('success', 'Devis créé avec succès.');
    }

    /**
     * Show the form for editing the specified devis.
     */

    public function edit(Devis $devis)
    {
        $freelancers = User::role('Freelancer')->get(); // Assuming Spatie roles are used
        return view('devis.edit', compact('devis', 'freelancers'));
    }

    public function update(Request $request, Devis $devis)
    {
        $validated = $request->validate([
            'freelance_id' => 'nullable|exists:users,id',
            'montant' => 'required|numeric',
            'statut' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $devis->update($validated);

        return redirect()->route('devis.index')->with('success', 'Devis mis à jour avec succès.');
    }


    /**
     * Remove the specified devis from storage.
     */


    public function destroy(Devis $devis)
    {
        try {
            Gate::authorize('delete-devis', $devis);

            Log::info("Deleting devis with ID: " . $devis->id);

            $devis->forceDelete();

            Log::info("Devis deleted successfully.");

            return redirect()->route('devis.index')->with('success', 'Devis supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Error deleting devis: " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur s’est produite lors de la suppression.');
        }
    }

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
     * Authorize the user based on roles.
     */
}
