<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        // Apply middleware to ensure only authenticated freelancers can access these routes
        $this->middleware(['auth', 'role:Freelancer|Account Manager|Admin|Super Admin']);
    }

    /**
     * Display a listing of the contacts.
     */
    public function index()
    {
        // Retrieve all contacts (including soft-deleted ones) for the authenticated freelancer
        $contacts = auth()->user()->contacts()->withTrashed()->get();

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'telephone' => 'nullable|string|max:20',
        ]);

        // Create a new contact for the authenticated freelancer
        Contact::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'freelancer_id' => auth()->id(),
            'statut' => 'actif',
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact ajouté avec succès.');
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact)
    {
        // Ensure the contact belongs to the authenticated freelancer
        $this->authorize('update', $contact);

        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        // Ensure the contact belongs to the authenticated freelancer
        $this->authorize('update', $contact);

        // Validate the incoming request
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'telephone' => 'nullable|string|max:20',
            'statut' => 'required|in:actif,archive',
        ]);

        // Update the contact
        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('success', 'Contact mis à jour avec succès.');
    }

    /**
     * Soft delete the specified contact.
     */
    public function destroy(Contact $contact)
    {
        // Ensure the contact belongs to the authenticated freelancer
        $this->authorize('delete', $contact);

        $contact->delete(); // Soft delete

        return redirect()->route('contacts.index')->with('success', 'Contact archivé avec succès.');
    }

    /**
     * Restore a soft-deleted contact.
     */
    public function restore($id)
    {
        // Find the contact (including soft-deleted ones)
        $contact = Contact::withTrashed()->findOrFail($id);

        // Ensure the contact belongs to the authenticated freelancer
        $this->authorize('restore', $contact);

        $contact->restore();

        return redirect()->route('contacts.index')->with('success', 'Contact restauré avec succès.');
    }
}
