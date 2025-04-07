<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Rdv;
use App\Models\Devis;
use App\Models\Commission;
use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Use middleware in the controller constructor
    public function __construct()
    {
        $this->middleware('auth'); // Ensures only authenticated users can access
    }

    /**
     * Display the dashboard view with user-specific data.
     */
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('Freelancer')) {
            $data['contacts'] = Contact::where('freelancer_id', $user->id)->count();
            $data['rdvs'] = Rdv::where('freelancer_id', $user->id)->where('statut', 'planifié')->count();
            $data['devis'] = Devis::where('freelancer_id', $user->id)->count();
            $data['commissions'] = Commission::where('freelancer_id', $user->id)->count();
            $data['pending_commissions'] = Commission::where('freelancer_id', $user->id)->where('statut', 'en attente')->count();
            $data['approved_commissions'] = Commission::where('freelancer_id', $user->id)->where('statut', 'validé')->count();
            $data['abonnement'] = $user->abonnement; // Assuming this relationship exists
        } elseif ($user->hasRole('Account Manager')) {
            $data['rdvs'] = Rdv::where('manager_id', $user->id)->count();
            $data['devis'] = Devis::whereIn('rdv_id', Rdv::where('manager_id', $user->id)->pluck('id'))->count();
            $data['pending_commissions'] = Commission::where('statut', 'en attente')->count();
        } else { // Admin or Super Admin
            $data['users'] = User::count();
            $data['contacts'] = Contact::count();
            $data['rdvs'] = Rdv::count();
            $data['devis'] = Devis::count();
            $data['commissions'] = Commission::count();
            $data['pending_commissions'] = Commission::where('statut', 'en attente')->count();
            $data['approved_commissions'] = Commission::where('statut', 'validé')->count();
            $data['abonnements'] = Abonnement::count(); // Assuming Abonnement model exists
        }

        return view('dashboard', compact('data'));
    }
}
