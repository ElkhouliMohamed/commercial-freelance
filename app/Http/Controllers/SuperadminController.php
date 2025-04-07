<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Devis;
use App\Models\Plan;
use App\Models\Rdv;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:Super Admin']);
    }

   public function index()
{
    $rdvCount = Rdv::count();
    $contactCount = Contact::count();
    $devisCount = Devis::count();
    
    // Change take(5) to paginate(5)
    $latestRdvs = Rdv::latest()->paginate(5, ['*'], 'rdvs_page');
    $latestContacts = Contact::latest()->paginate(5, ['*'], 'contacts_page');
    $latestDevis = Devis::latest()->paginate(5, ['*'], 'devis_page');

    return view('admin.index', compact(
        'rdvCount',
        'contactCount',
        'devisCount',
        'latestRdvs',
        'latestContacts',
        'latestDevis'
    ));
}
}
