<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Models\Plan;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Plans View Route (Public)
Route::get('/abonnements/plans', function () {
    $plans = Plan::all();
    return view('Abonnements.plans', compact('plans'));
})->name('abonnements.plans');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/rdvs', [RdvController::class, 'index']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Common Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('verified');

    // Contacts Routes - Accessible to all authenticated users for their own contacts
    Route::resource('contacts', ContactController::class)->except(['destroy']);
    Route::put('/contacts/restore/{contact}', [ContactController::class, 'restore'])
        ->name('contacts.restore')
        ->middleware('can:restore,contact');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])
        ->name('contacts.destroy')
        ->middleware('can:delete,contact');

    // Role-specific routes
    Route::middleware(['role:Freelancer'])->group(function () {
        Route::get('/freelancer/dashboard', [DashboardController::class, 'index'])->name('freelancer.dashboard');
        Route::resource('rdvs',  RdvController::class);

        // Commission Routes
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
        Route::post('/commissions', [CommissionController::class, 'store'])->name('commissions.store');
    });

    Route::middleware(['role:Freelancer|Account Manager|Admin|Super Admin'])->group(function () {
        Route::resource('rdvs', RdvController::class);
    });

    Route::middleware(['role:Account Manager'])->group(function () {
        Route::get('/account-manager/dashboard', [DashboardController::class, 'index'])->name('account_manager.dashboard');
        Route::resource('devis', DevisController::class)->except(['create', 'show']);
        Route::resource('rdvs', controller: RdvController::class);
        Route::get('/devis/create/{rdvId}', [DevisController::class, 'create'])->name('devis.create');
        Route::get('/devis/{devis}', [DevisController::class, 'show'])->name('devis.show');
        Route::put('/devis/{devis}/validate', [DevisController::class, 'validateDevis'])->name('devis.validate');
    });

    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Subscription Management
        Route::resource('abonnements', AbonnementController::class);
        Route::get('/abonnements/active', [AbonnementController::class, 'active'])->name('abonnements.active');
        Route::put('/abonnements/{abonnement}/reset-commission', [AbonnementController::class, 'resetCommission'])
            ->name('abonnements.reset-commission');

        // User Management
        Route::resource('users', UserController::class);

        // Plan Management
        Route::resource('plans', PlanController::class);

        // Commission Approval
        Route::put('/commissions/{commission}/approve', [CommissionController::class, 'approve'])
            ->name('commissions.approve');
    });
});

require __DIR__ . '/auth.php';
