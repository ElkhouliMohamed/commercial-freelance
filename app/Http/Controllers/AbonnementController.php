<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\User;
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
        $abonnements = Abonnement::paginate(10); // Paginate results with 10 items per page
        return view('abonnements.index', compact('abonnements'));
    }

    public function create()
    {
        // Fetch all users with the 'Freelancer' role
        $freelancers = User::role('Freelancer')->get();

        // Pass the freelancers to the view
        return view('Abonnements.create', compact('freelancers'));
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

    public function calculateCommission()
    {
        $levels = [
            ['level' => 'Bronze', 'min' => 1, 'max' => 10, 'commission' => 500],
            ['level' => 'Argent', 'min' => 11, 'max' => 20, 'commission' => 1000],
            ['level' => 'Or', 'min' => 21, 'max' => 30, 'commission' => 1500],
            ['level' => 'Platine', 'min' => 31, 'max' => PHP_INT_MAX, 'commission' => 2000],
        ];

        foreach ($levels as $level) {
            if ($this->contracts_count >= $level['min'] && $this->contracts_count <= $level['max']) {
                return [
                    'level' => $level['level'],
                    'commission' => $level['commission'],
                ];
            }
        }

        return ['level' => 'None', 'commission' => 0];
    }

    public function resetCommission(Abonnement $abonnement)
    {
        $abonnement->update(['contracts_count' => 0]);

        return redirect()->route('abonnements.index')->with('success', 'Commission réinitialisée avec succès.');
    }
}
