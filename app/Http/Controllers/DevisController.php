<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Contact;
use App\Models\Rdv;
use App\Models\User;
use Illuminate\Http\Request;

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
        $this->authorizeRole(['Freelancer', 'Admin']);

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
        // Check if the user has the required role
        $this->authorizeRole(['Freelancer', 'Admin']);

        $contacts = Contact::all();
        $rdvs = Rdv::all();

        return view('devis.edit', compact('devis', 'contacts', 'rdvs'));
    }

    /**
     * Update the specified devis in storage.
     */
    public function update(Request $request, Devis $devis)
    {
        // Check if the user has the required role
        $this->authorizeRole(['Freelancer', 'Admin']);

        // Validate the incoming request
        $validatedData = $request->validate([
            'rdv_id' => 'required|exists:rdvs,id',
            'contact_id' => 'required|exists:contacts,id',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:en attente,validé,refusé',
        ]);

        // Update the devis
        $devis->update($validatedData);

        return redirect()->route('devis.index')->with('success', 'Devis mis à jour avec succès.');
    }

    /**
     * Remove the specified devis from storage.
     */
    public function destroy(Devis $devis)
    {
        // Check if the user has the required role
        $this->authorizeRole(['Freelancer', 'Admin']);

        $devis->delete();

        return redirect()->route('devis.index')->with('success', 'Devis supprimé avec succès.');
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
    private function authorizeRole(array $roles)
    {
        if (!auth()->user()->hasAnyRole($roles)) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }
    }
}
