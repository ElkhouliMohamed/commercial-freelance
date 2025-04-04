<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:Freelancer'], ['only' => ['create', 'store']]);
        $this->middleware(['role:Admin|Super Admin'], ['only' => ['index', 'approve']]);
    }

    public function index()
    {
        $commissions = Commission::all();
        return view('commissions.index', compact('commissions'));
    }

    public function create()
    {
        return view('commissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Commission::create([
            'freelancer_id' => Auth::id(),
            'montant' => $request->montant,
            'description' => $request->description,
            'statut' => 'en attente',
            'demande_paiement' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Demande de commission envoyée avec succès.');
    }

    public function approve(Commission $commission)
    {
        $commission->update(['statut' => 'validé']);

        // Envoyer une notification (optionnel, à implémenter)
        return redirect()->route('commissions.index')->with('success', 'Commission approuvée.');
    }
}
