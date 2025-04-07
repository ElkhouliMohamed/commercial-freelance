<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        if (Auth::user()->hasRole('Super Admin')) {
            // Super Admin can see all contacts
            $contacts = Contact::where('statut', 'actif')
                ->latest()
                ->paginate($perPage);
        } else {
            $contacts = Auth::user()->contacts()
                ->where('statut', 'actif')
                ->latest()
                ->paginate($perPage);
        }

        return view('contacts.index', compact('contacts'));
    }
    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'nom_entreprise' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255|url',
            'facebook' => 'nullable|string|max:255|url',
            'siteweb' => 'nullable|string|max:255|url',
        ]);

        $contact = Auth::user()->contacts()->create($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact créé avec succès.');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:contacts,email,' . $contact->id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'nom_entreprise' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255|url',
            'facebook' => 'nullable|string|max:255|url',
            'siteweb' => 'nullable|string|max:255|url',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact mis à jour avec succès.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact archivé avec succès.');
    }

    public function restore(Contact $contact)
    {
        $contact->restore();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact restauré avec succès.');
    }
}
       