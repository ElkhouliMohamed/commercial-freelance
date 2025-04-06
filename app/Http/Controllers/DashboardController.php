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

        // Initialize the data array with default values
        $data = [
            'contacts' => 0,
            'rdvs' => 0,
            'devis' => 0,
            'commissions' => 0,
            'abonnement' => null,
            'users' => 0,
            'abonnements' => 0,
            'type_user' => "none",
        ];

        // Check user role and prepare data accordingly
        if ($user->hasRole('Freelancer')) {
            $data['contacts'] = Contact::where('freelancer_id', $user->id)->count();
            $data['rdvs'] = Rdv::where('freelancer_id', $user->id)->count();
            $data['devis'] = Devis::whereIn('rdv_id', Rdv::where('freelancer_id', $user->id)->pluck('id'))->count();
            $data['commissions'] = Commission::where('freelancer_id', $user->id)->count();
            $data['abonnement'] = Abonnement::where('freelancer_id', operator: $user->id)->first();
            $data['type_user'] = "Freelancer";
        } elseif ($user->hasRole('Account Manager')) {
            $data['rdvs'] = Rdv::where('manager_id', $user->id)->count();
            $data['devis'] = Devis::whereIn('rdv_id', Rdv::where('manager_id', $user->id)->pluck('id'))->count();
            $data['type_user'] = "account_manager";
        } elseif ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            $data['users'] = User::count();
            $data['contacts'] = Contact::count();
            $data['rdvs'] = Rdv::count();
            $data['devis'] = Devis::count();
            $data['commissions'] = Commission::count();
            $data['abonnements'] = Abonnement::count();
            $data['type_user'] = "admin";
        }

        return view('dashboard', compact('data'));
    }
}
