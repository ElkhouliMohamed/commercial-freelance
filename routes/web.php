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

// 🔐 Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// 💳 Plans Public Page
Route::get('/abonnements/plans', function () {
    $plans = Plan::all();
    return view('Abonnements.plans', compact('plans'));
})->name('abonnements.plans');

// ✅ Authenticated Users
Route::middleware(['auth'])->group(function () {

    // Common Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Common Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('verified');

    // ✅ Everyone can access RDVs
    Route::resource('rdvs', RdvController::class)->middleware('can:manage rdvs');


    // Contacts CRUD for all authenticated users
    Route::resource('contacts', ContactController::class)->except(['destroy']);
    Route::put('/contacts/restore/{contact}', [ContactController::class, 'restore'])
        ->name('contacts.restore')
        ->middleware('can:restore,contact');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])
        ->name('contacts.destroy')
        ->middleware('can:delete,contact');

    // 👤 Freelancer-only routes
    Route::middleware(['role:Freelancer'])->group(function () {
        Route::get('/freelancer/dashboard', [DashboardController::class, 'index'])->name('freelancer.dashboard');

        // Commissions
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
        Route::post('/commissions', [CommissionController::class, 'store'])->name('commissions.store');
    });

    // 👤 Account Manager routes
    Route::middleware(['role:Account Manager'])->group(function () {
        Route::get('/account-manager/dashboard', [DashboardController::class, 'index'])->name('account_manager.dashboard');

        Route::resource('devis', DevisController::class)->except(['create', 'show']);
        Route::get('/devis/create/{rdvId}', [DevisController::class, 'create'])->name('devis.create');
        Route::get('/devis/{devis}', [DevisController::class, 'show'])->name('devis.show');
        Route::put('/devis/{devis}/validate', [DevisController::class, 'validateDevis'])->name('devis.validate');
    });

    // 👑 Super Admin routes
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Subscriptions
        Route::resource('abonnements', AbonnementController::class);
        Route::get('/abonnements/active', [AbonnementController::class, 'active'])->name('abonnements.active');
        Route::put('/abonnements/{abonnement}/reset-commission', [AbonnementController::class, 'resetCommission'])->name('abonnements.reset-commission');

        // Users
        Route::resource('users', UserController::class);

        // Plans
        Route::resource('plans', PlanController::class);

        // Approve commissions
        Route::put('/commissions/{commission}/approve', [CommissionController::class, 'approve'])->name('commissions.approve');
    });
});

// ✅ Optional: Verified-only RDVs (if needed again)
Route::middleware(['auth', 'verified'])->group(function () {
    // No need to repeat 'rdvs' route here again unless for extra logic
});

require __DIR__ . '/auth.php';
