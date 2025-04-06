<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommissionController extends Controller
{
    const COMMISSION_LEVELS = [
        'Bronze' => ['min_contracts' => 1, 'max_contracts' => 10, 'fixed_amount' => 500],
        'Silver' => ['min_contracts' => 11, 'max_contracts' => 20, 'fixed_amount' => 1000],
        'Gold' => ['min_contracts' => 21, 'max_contracts' => 30, 'fixed_amount' => 1500],
        'Platinum' => ['min_contracts' => 31, 'fixed_amount' => 2000]
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Freelancer', ['only' => ['create', 'store']]);
/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Show the form for creating a new commission.
     *
     * @return \Illuminate\View\View
     */

/*******  fdbf2b83-f76e-4a4e-afcb-93d67b308f52  *******/        $this->middleware('role:Account Manager', ['only' => ['approve']]);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Freelancer')) {
            $commissions = $user->commissions()->latest()->get();
        } else {
            $commissions = Commission::with('freelancer')->latest()->get();
        }

        return view('commissions.index', compact('commissions'));
    }

    public function create()
    {
        $contractCount = $this->getValidContractCount();
        $commissionLevel = $this->getCommissionLevel($contractCount);

        if (!$commissionLevel) {
            return redirect()->route('commissions.index')
                ->with('error', 'Vous avez besoin d\'au moins 1 contrat validé pour demander une commission.');
        }

        return view('commissions.create', [
            'contractCount' => $contractCount,
            'commissionLevel' => $commissionLevel,
            'eligibleAmount' => $commissionLevel['fixed_amount']
        ]);
    }

    public function store(Request $request)
    {
        $contractCount = $this->getValidContractCount();
        $commissionLevel = $this->getCommissionLevel($contractCount);

        if (!$commissionLevel) {
            return back()->with('error', 'Vous n\'avez pas assez de contrats pour demander une commission.');
        }

        // Check if freelancer already has a pending commission
        $pendingCommission = Auth::user()->commissions()
            ->where('statut', 'en attente')
            ->exists();

        if ($pendingCommission) {
            return back()->with('error', 'Vous avez déjà une demande de commission en attente.');
        }

        Commission::create([
            'freelancer_id' => Auth::id(),
            'montant' => $commissionLevel['fixed_amount'],
            'description' => "Commission {$commissionLevel['name']} pour {$contractCount} contrats",
            'statut' => 'en attente',
            'niveau' => $commissionLevel['name'],
            'nombre_contrats' => $contractCount,
        ]);

        return redirect()->route('commissions.index')
            ->with('success', 'Demande de commission envoyée avec succès.');
    }

    public function approve(Request $request, Commission $commission)
    {
        $request->validate([
            'proof' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Store the payment proof
        $path = $request->file('proof')->store('payment_proofs');

        $commission->update([
            'statut' => 'validé',
            'payment_proof_path' => $path,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Reset all devis counts for this freelancer
        Devis::where('freelancer_id', $commission->freelancer_id)
            ->update(['compte_pour_commission' => false]);

        // Send notification to freelancer (to be implemented)
        // Notification::send($commission->freelancer, new CommissionApproved($commission));

        return redirect()->route('commissions.index')
            ->with('success', 'Commission approuvée et compteur remis à zéro.');
    }

    public function showProof(Commission $commission)
    {
        $this->authorize('view', $commission);

        return response()->file(storage_path('app/' . $commission->payment_proof_path));
    }

    private function getValidContractCount()
    {
        return Devis::where('freelancer_id', Auth::id())
            ->where('compte_pour_commission', true)
            ->where('statut', 'validé')
            ->count();
    }

    private function getCommissionLevel($contractCount)
    {
        foreach (self::COMMISSION_LEVELS as $name => $level) {
            if (
                $contractCount >= $level['min_contracts'] &&
                (empty($level['max_contracts']) || $contractCount <= $level['max_contracts'])
            ) {
                return ['name' => $name] + $level;
            }
        }
        return null;
    }
}
