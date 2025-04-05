<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AssignedToRdv;


class RdvController extends Controller
{
    public function __construct()
    {
        // Restrict access to authenticated users with the "Freelancer" role
        $this->middleware(['auth', 'role:Freelancer|Account Manager']);
    }

    /**
     * Display a listing of the RDVs for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Freelancer')) {
            // Show RDVs assigned to the freelancer
            $rdvs = Rdv::where('freelancer_id', $user->id)->with(['contact'])->get();
        } elseif ($user->hasRole('Account Manager')) {
            // Show RDVs assigned to the account manager
            $rdvs = Rdv::where('manager_id', $user->id)->with(['contact'])->get();
        } else {
            // Redirect if the user does not have the required role
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        return view('rdvs.index', compact('rdvs'));
    }

    /**
     * Show the form for creating a new RDV.
     */
    public function create()
    {
        // Get active contacts for the authenticated freelancer
        $contacts = Contact::where('freelancer_id', auth()->id())
            ->where('statut', 'actif')
            ->get();

        return view('rdvs.create', compact('contacts'));
    }

    /**
     * Store a newly created RDV in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'date' => 'required|date|after:now',
            'type' => 'required|string|max:255',
        ]);

        $manager = User::role('Account Manager')->inRandomOrder()->first();

        if (!$manager) {
            return redirect()->route('rdvs.index')->with('error', 'Aucun Account Manager disponible.');
        }

        $rdv = Rdv::create([
            'contact_id' => $request->contact_id,
            'freelancer_id' => auth()->id(),
            'manager_id' => $manager->id,
            'date' => $request->date,
            'type' => $request->type,
            'statut' => 'planifié',
        ]);

        // Send notification to the assigned Account Manager
        $manager->notify(new AssignedToRdv($rdv));

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous créé avec succès et assigné à un Account Manager.');
    }

    /**
     * Show the form for editing the specified RDV.
     */
    public function edit(Rdv $rdv)
    {
        // Ensure the RDV belongs to the authenticated freelancer
        if ($rdv->freelancer_id !== auth()->id()) {
            return redirect()->route('rdvs.index')->with('error', 'Accès non autorisé.');
        }

        $contacts = Contact::where('freelancer_id', auth()->id())
            ->where('statut', 'actif')
            ->get();

        return view('rdvs.edit', compact('rdv', 'contacts'));
    }

    /**
     * Update the specified RDV in storage.
     */
    public function update(Request $request, Rdv $rdv)
    {
        // Ensure the RDV belongs to the authenticated freelancer
        if ($rdv->freelancer_id !== auth()->id()) {
            return redirect()->route('rdvs.index')->with('error', 'Accès non autorisé.');
        }

        // Validate the incoming request
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'date' => 'required|date|after:now',
            'type' => 'required|string|max:255',
        ]);

        // Update the RDV
        $rdv->update([
            'contact_id' => $request->contact_id,
            'date' => $request->date,
            'type' => $request->type,
        ]);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    /**
     * Remove the specified RDV from storage.
     */
    public function destroy(Rdv $rdv)
    {
        // Ensure the RDV belongs to the authenticated freelancer
        if ($rdv->freelancer_id !== auth()->id()) {
            return redirect()->route('rdvs.index')->with('error', 'Accès non autorisé.');
        }

        $rdv->delete();

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous supprimé avec succès.');
    }
}
