<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\CommissionApproved; // Ensure this event exists

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
        $this->middleware('role:Account Manager|Admin', ['only' => ['approve', 'showProof']]);
    }

    /**
     * Display a listing of commissions for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $commissions = Commission::where('freelancer_id', $user->id)
            ->with('devis') // Eager load devis for additional context
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate for better performance

        $stats = $this->getCommissionStats($commissions);

        return view('commissions.index', compact('commissions', 'stats'));
    }

    /**
     * Show the form for creating a new commission.
     */
    public function create()
    {
        $contractCount = $this->getValidContractCount();

        if ($contractCount < 1) {
            return redirect()->route('commissions.index')
                ->with('warning', 'Vous avez besoin d\'au moins 1 contrat validé pour demander une commission.');
        }

        $commissionLevel = $this->getCommissionLevel($contractCount);
        $hasPendingCommission = Auth::user()->commissions()
            ->where('statut', 'En Attente') // Standardized to match DevisController
            ->exists();

        if ($hasPendingCommission) {
            return redirect()->route('commissions.index')
                ->with('warning', 'Une demande de commission est déjà en attente.');
        }

        return view('commissions.create', compact('contractCount', 'commissionLevel', 'hasPendingCommission'));
    }

    /**
     * Store a newly created commission in storage.
     */
    public function store(Request $request)
    {
        $contractCount = $this->getValidContractCount();
        $commissionLevel = $this->getCommissionLevel($contractCount);

        if (!$commissionLevel) {
            return back()->with('error', 'Conditions non remplies pour demander une commission.');
        }

        $commission = Commission::create([
            'freelancer_id' => Auth::id(),
            'montant' => $commissionLevel['fixed_amount'],
            'description' => "Commission {$commissionLevel['name']} - {$contractCount} contrats validés",
            'statut' => 'En Attente', // Standardized status
            'niveau' => $commissionLevel['name'],
            'nombre_contrats' => $contractCount,
        ]);

        Log::channel('commissions')->info('Nouvelle commission créée', [
            'id' => $commission->id,
            'freelancer' => Auth::user()->name,
            'montant' => $commission->montant,
            'contrats' => $contractCount,
        ]);

        return redirect()->route('commissions.index')
            ->with('success', 'Votre demande de commission a été enregistrée avec succès.');
    }

    /**
     * Approve a commission and store payment proof.
     */
    public function approve(Request $request, Commission $commission)
    {
        $this->authorize('approve', $commission); // Ensure policy or gate is defined

        $request->validate([
            'proof' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'payment_date' => 'required|date|after_or_equal:now',
        ]);

        try {
            $filename = Str::uuid() . '.' . $request->file('proof')->extension();
            $path = $request->file('proof')->storeAs('payment_proofs', $filename, 'public');

            $commission->update([
                'statut' => 'Payé', // Standardized status
                'payment_proof_path' => $path,
                'payment_date' => $request->payment_date,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Reset commission counter for this freelancer
            Devis::where('freelancer_id', $commission->freelancer_id)
                ->where('compte_pour_commission', true)
                ->update(['compte_pour_commission' => false]);

            // Fire event for notification
            event(new CommissionApproved($commission));

            Log::channel('commissions')->notice('Commission approuvée', [
                'id' => $commission->id,
                'approbateur' => Auth::user()->name,
                'montant' => $commission->montant,
            ]);

            return redirect()->route('commissions.index')
                ->with('success', 'Paiement confirmé et compteur remis à zéro.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'approbation de la commission: ' . $e->getMessage());
            return back()->with('error', 'Une erreur s’est produite lors de la confirmation du paiement.');
        }
    }

    /**
     * Display the payment proof file.
     */
    public function showProof(Commission $commission)
    {
        $this->authorize('viewProof', $commission); // Ensure policy or gate is defined

        if (!$commission->payment_proof_path || !Storage::disk('public')->exists($commission->payment_proof_path)) {
            abort(404, 'Preuve de paiement non trouvée.');
        }

        return response()->file(storage_path('app/public/' . $commission->payment_proof_path));
    }

    /**
     * Get commission statistics.
     */
    private function getCommissionStats($commissions)
    {
        // Filter out null values before counting
        $levels = array_filter($commissions->pluck('niveau')->toArray(), function ($value) {
            return !is_null($value);
        });

        return [
            'total_amount' => $commissions->sum('montant'),
            'pending' => $commissions->where('statut', 'En Attente')->count(),
            'paid' => $commissions->where('statut', 'Payé')->count(),
            'levels' => array_count_values($levels) // Now only contains valid values
        ];
    }

    /**
     * Get the count of valid contracts for the authenticated freelancer.
     */
    private function getValidContractCount(): int
    {
        return Devis::where('freelancer_id', Auth::id())
            ->where('statut', 'validé') // Match DevisController status
            ->where('compte_pour_commission', true)
            ->count();
    }

    /**
     * Determine the commission level based on contract count.
     */
    private function getCommissionLevel(int $contractCount): ?array
    {
        foreach (self::COMMISSION_LEVELS as $name => $level) {
            $minCondition = $contractCount >= $level['min_contracts'];
            $maxCondition = !isset($level['max_contracts']) || $contractCount <= $level['max_contracts'];

            if ($minCondition && $maxCondition) {
                return ['name' => $name] + $level;
            }
        }
        return null;
    }
}
