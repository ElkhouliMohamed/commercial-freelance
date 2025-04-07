<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\CommissionApproved;
use App\Models\User;
use Carbon\Carbon;

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
        $this->middleware('role:Freelancer', ['only' => ['create', 'store', 'show']]);
        $this->middleware('role:Account Manager|Admin', ['only' => ['approve', 'showProof']]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        
        $query = Commission::where('freelancer_id', $user->id)
            ->with('devis')
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('statut', $request->status);
        }

        $commissions = $query->paginate($perPage);
        $stats = $this->getCommissionStats($commissions);

        return view('commissions.index', compact('commissions', 'stats'));
    }

    public function create()
    {
        $contractCount = $this->getValidContractCount();
        
        if ($contractCount < 1) {
            return redirect()->route('commissions.index')
                ->with('warning', 'Vous avez besoin d\'au moins 1 contrat validé ou accepté.');
        }

        $commissionLevel = $this->getCommissionLevel($contractCount);
        $hasPendingCommission = Commission::where('freelancer_id', Auth::id())
            ->where('statut', 'En Attente')
            ->exists();

        if ($hasPendingCommission) {
            return redirect()->route('commissions.index')
                ->with('warning', 'Une demande de commission est déjà en attente.');
        }

        return view('commissions.create', compact('contractCount', 'commissionLevel'));
    }

    public function store(Request $request)
    {
        $contractCount = $this->getValidContractCount();
        $commissionLevel = $this->getCommissionLevel($contractCount);

        if (!$commissionLevel) {
            return back()->with('error', 'Conditions non remplies pour demander une commission.');
        }

        $commission = Commission::create([
            'freelancer_id' => Auth::id(),
            'montant' => $commissionLevel['fixed_amount'] * $contractCount,
            'description' => "Commission {$commissionLevel['name']} - {$contractCount} contrats",
            'statut' => 'En Attente',
            'niveau' => $commissionLevel['name'],
            'nombre_contrats' => $contractCount,
            'month' => Carbon::now()->format('Y-m'),
        ]);

        Log::channel('commissions')->info('Nouvelle commission créée', [
            'id' => $commission->id,
            'freelancer' => Auth::user()->name,
            'montant' => $commission->montant,
            'contrats' => $contractCount,
        ]);

        return redirect()->route('commissions.index')
            ->with('success', 'Demande de commission enregistrée avec succès.');
    }

    public function approve(Request $request, Commission $commission)
    {
        $this->authorize('approve', $commission);

        $request->validate([
            'proof' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'payment_date' => 'required|date|after_or_equal:now',
        ]);

        return DB::transaction(function () use ($request, $commission) {
            $filename = Str::uuid() . '.' . $request->file('proof')->extension();
            $path = $request->file('proof')->storeAs('payment_proofs', $filename, 'public');

            $commission->update([
                'statut' => 'Payé',
                'payment_proof_path' => $path,
                'payment_date' => $request->payment_date,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            Devis::where('freelancer_id', $commission->freelancer_id)
                ->where('compte_pour_commission', true)
                ->update(['compte_pour_commission' => false]);

            event(new CommissionApproved($commission));

            Log::channel('commissions')->notice('Commission approuvée', [
                'id' => $commission->id,
                'approbateur' => Auth::user()->name,
                'montant' => $commission->montant,
            ]);

            return redirect()->route('commissions.index')
                ->with('success', 'Paiement confirmé et compteur remis à zéro.');
        });
    }

    public function showProof(Commission $commission)
    {
        $this->authorize('viewProof', $commission);

        if (!$commission->payment_proof_path || !Storage::disk('public')->exists($commission->payment_proof_path)) {
            abort(404, 'Preuve de paiement non trouvée.');
        }

        return response()->file(storage_path('app/public/' . $commission->payment_proof_path));
    }

    public function show(Commission $commission)
    {
        $this->authorize('view', $commission);
        return view('commissions.show', compact('commission'));
    }

    public function generateMonthlyCommissions(Request $request)
    {
        $this->middleware('role:Admin');
        
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        return DB::transaction(function () use ($startOfMonth, $endOfMonth, $month) {
            $freelancers = User::role('Freelancer')->get();

            foreach ($freelancers as $freelancer) {
                $contractCount = Devis::where('freelancer_id', $freelancer->id)
                    ->whereIn('statut', ['validé', 'Accepté'])
                    ->where('compte_pour_commission', true)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();

                if ($contractCount > 0 && !Commission::where('freelancer_id', $freelancer->id)
                    ->where('month', $month)
                    ->exists()) {
                    
                    $commissionLevel = $this->getCommissionLevel($contractCount);
                    if ($commissionLevel) {
                        Commission::create([
                            'freelancer_id' => $freelancer->id,
                            'montant' => $commissionLevel['fixed_amount'] * $contractCount,
                            'description' => "Commission mensuelle {$commissionLevel['name']} - {$contractCount} contrats",
                            'statut' => 'En Attente',
                            'niveau' => $commissionLevel['name'],
                            'nombre_contrats' => $contractCount,
                            'month' => $month,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', "Commissions pour $month générées avec succès.");
        });
    }

    private function getCommissionStats($commissions)
    {
        $levels = array_filter($commissions->pluck('niveau')->toArray());
        
        return [
            'total_amount' => $commissions->sum('montant'),
            'pending' => $commissions->where('statut', 'En Attente')->count(),
            'paid' => $commissions->where('statut', 'Payé')->count(),
            'average_amount' => $commissions->avg('montant'),
            'levels' => array_count_values($levels),
            'total_contracts' => $commissions->sum('nombre_contrats'),
        ];
    }

    private function getValidContractCount($month = null): int
    {
        $query = Devis::where('freelancer_id', Auth::id())
            ->whereIn('statut', ['validé', 'Accepté'])
            ->where('compte_pour_commission', true);

        if ($month) {
            $startOfMonth = Carbon::parse($month)->startOfMonth();
            $endOfMonth = Carbon::parse($month)->endOfMonth();
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }

        return $query->count();
    }

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

    public static function checkAndCreateCommission($freelancerId)
    {
        $month = Carbon::now()->format('Y-m');
        
        $contractCount = Devis::where('freelancer_id', $freelancerId)
            ->whereIn('statut', ['validé', 'Accepté'])
            ->where('compte_pour_commission', true)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $hasPendingCommission = Commission::where('freelancer_id', $freelancerId)
            ->where('statut', 'En Attente')
            ->where('month', $month)
            ->exists();

        if ($contractCount > 0 && !$hasPendingCommission) {
            $commissionLevel = self::getCommissionLevelStatic($contractCount);

            if ($commissionLevel) {
                Commission::create([
                    'freelancer_id' => $freelancerId,
                    'montant' => $commissionLevel['fixed_amount'] * $contractCount,
                    'description' => "Commission {$commissionLevel['name']} - {$contractCount} contrats",
                    'statut' => 'En Attente',
                    'niveau' => $commissionLevel['name'],
                    'nombre_contrats' => $contractCount,
                    'month' => $month,
                ]);
            }
        }
    }

    private static function getCommissionLevelStatic(int $contractCount): ?array
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