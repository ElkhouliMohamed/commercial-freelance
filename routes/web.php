<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanController;
use App\Models\Plan;

// 🌍 Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * 🔐 Authentication Routes
 * Routes accessible to guests only (unauthenticated users).
 */
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

/**
 * 💳 Public Plans Page
 * Display available subscription plans to all users.
 */
Route::get('/abonnements/plans', function () {
    $plans = Plan::all();
    return view('Abonnements.plans', compact('plans'));
})->name('abonnements.plans');

/**
 * ✅ Authenticated User Routes
 * Routes accessible only to authenticated users.
 */
Route::middleware(['auth'])->group(function () {

    // Common Logout Route
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Common Dashboard Route (requires email verification)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('verified');

    /**
     * ✅ Everyone can access RDVs and Contacts
     * Routes for managing appointments and contacts, with authorization checks.
     */
    Route::resource('rdvs', RdvController::class)->middleware('can:manage rdvs');

    Route::resource('contacts', ContactController::class)->except(['destroy']);
    Route::put('/contacts/restore/{contact}', [ContactController::class, 'restore'])
        ->name('contacts.restore')
        ->middleware('can:restore,contact');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])
        ->name('contacts.destroy')
        ->middleware('can:delete,contact');

    /**
     * 👤 Freelancer-only Routes
     * Routes accessible only to users with the Freelancer role.
     */
    Route::middleware(['role:Freelancer'])->group(function () {
        Route::get('/freelancer/dashboard', [DashboardController::class, 'index'])->name('freelancer.dashboard');

        // Commission Management
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
        Route::post('/commissions', [CommissionController::class, 'store'])->name('commissions.store');
    });

    /**
     * 👤 Account Manager Routes
     * Routes accessible only to users with the Account Manager role.
     */
    Route::middleware(['role:Account Manager'])->group(function () {
        Route::get('/account-manager/dashboard', [DashboardController::class, 'index'])->name('account_manager.dashboard');

        // Devis Management (Quotes)
        Route::resource('devis', DevisController::class, ['parameters' => ['devis' => 'devis']])
            ->except(['create', 'show']);

        Route::get('/devis/create/{rdvId}', [DevisController::class, 'create'])->name('devis.create');
        Route::get('/devis/{devis}', [DevisController::class, 'show'])->name('devis.show');
        Route::put('/devis/{devis}/validate', [DevisController::class, 'validateDevis'])->name('devis.validate');
    });

    /**
     * 👑 Super Admin Routes
     * Routes accessible only to users with the Super Admin role.
     */
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Subscriptions Management
        Route::resource('abonnements', AbonnementController::class);
        Route::get('/abonnements/active', [AbonnementController::class, 'active'])->name('abonnements.active');
        Route::put('/abonnements/{abonnement}/reset-commission', [AbonnementController::class, 'resetCommission'])->name('abonnements.reset-commission');

        // Users Management
        Route::resource('users', UserController::class);

        // Plans Management
        Route::resource('plans', PlanController::class);

        // Commission Approval and Proof
        Route::put('/commissions/{commission}/approve', [CommissionController::class, 'approve'])->name('commissions.approve');
        Route::get('/commissions/{commission}/proof', [CommissionController::class, 'showProof'])->name('commissions.showProof');
    });
});

require __DIR__ . '/auth.php';
