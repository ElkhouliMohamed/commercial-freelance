<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:Admin|Super Admin']);
    }

    public function index()
    {
        $abonnements = Abonnement::all();
        return view('abonnements.index', compact('abonnements'));
    }

    public function create()
    {
        return view('abonnements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'freelancer_id' => 'required|exists:users,id',
            'plan' => 'required|in:Basic,Premium',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        Abonnement::create($request->all());

        return redirect()->route('abonnements.index')->with('success', 'Abonnement créé avec succès.');
    }

    public function show(Abonnement $abonnement)
    {
        return view('abonnements.show', compact('abonnement'));
    }

    public function edit(Abonnement $abonnement)
    {
        return view('abonnements.edit', compact('abonnement'));
    }

    public function update(Request $request, Abonnement $abonnement)
    {
        $request->validate([
            'plan' => 'required|in:Basic,Premium',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $abonnement->update($request->all());

        return redirect()->route('abonnements.index')->with('success', 'Abonnement mis à jour.');
    }

    public function destroy(Abonnement $abonnement)
    {
        $abonnement->delete();
        return redirect()->route('abonnements.index')->with('success', 'Abonnement supprimé.');
    }

    public function active()
    {
        $abonnements = Abonnement::where('date_fin', '>=', now())->get();
        return view('abonnements.active', compact('abonnements'));
    }
}
