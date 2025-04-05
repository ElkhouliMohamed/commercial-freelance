<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        // Apply middleware to ensure only authenticated freelancers can access these routes
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the contacts with pagination.
     */
    public function index()
    {
        // Retrieve paginated contacts (including soft-deleted ones) for the authenticated user
        $contacts = Auth::user()->contacts()->withTrashed()->paginate(10); // Adjust the number as needed

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
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'nom_entreprise' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'siteweb' => 'nullable|string|max:255',
            'statut' => 'nullable|string|in:actif,archive',
        ]);

        // Associate the contact with the authenticated user
        $validated['freelancer_id'] = Auth::id(); // This ensures the contact is linked to the logged-in user
        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact créé avec succès.');
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact)
    {
        // Ensure the contact belongs to the authenticated user
        $this->authorize('update', $contact);

        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $this->authorize('update', $contact);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'nom_entreprise' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'siteweb' => 'nullable|string|max:255',
            'freelancer_id' => 'nullable|exists:users,id',
            'statut' => 'nullable|string|in:actif,archive', // Updated to match Blade template
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact mis à jour avec succès.');
    }

    /**
     * Soft delete the specified contact.
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete(); // Soft delete

        return redirect()->route('contacts.index')->with('success', 'Contact archivé avec succès.');
    }

    /**
     * Restore a soft-deleted contact.
     */
    public function restore($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);

        $this->authorize('restore', $contact);

        $contact->restore();

        return redirect()->route('contacts.index')->with('success', 'Contact restauré avec succès.');
    }

    /**
     * Authorize that the contact belongs to the authenticated user.
     */
    protected function authorizeContact(Contact $contact)
    {
        if ($contact->freelancer_id !== Auth::id() && !Auth::user()->hasRole(['Admin', 'Super Admin'])) {
            abort(403, 'Unauthorized action.');
        }
    }
}
