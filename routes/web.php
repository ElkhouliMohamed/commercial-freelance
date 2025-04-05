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

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('verified');

    // Freelancer and Account Manager Shared Routes
    Route::middleware(['role:Freelancer|Account Manager'])->group(function () {
        Route::resource('contacts', ContactController::class);
        Route::put('/contacts/restore/{id}', [ContactController::class, 'restore'])->name('contacts.restore');
        Route::resource('rdvs', RdvController::class);
    });

    // Freelancer Dashboard
    Route::middleware(['role:Freelancer'])->group(function () {
        Route::get('/freelancer/dashboard', [DashboardController::class, 'index'])->name('freelancer.dashboard');
        
    });

    // Account Manager Dashboard
    Route::middleware(['role:Account Manager'])->group(function () {
        Route::get('/account-manager/dashboard', [DashboardController::class, 'index'])->name('account_manager.dashboard');
        Route::resource('devis', DevisController::class)->except(['create']);
        Route::get('/devis/create/{rdvId}', [DevisController::class, 'create'])->name('devis.create');
        Route::get('/devis/create/{rdvId?}', [DevisController::class, 'create'])->name('devis.create');
        Route::resource('contacts', ContactController::class);
        Route::put('/contacts/restore/{id}', [ContactController::class, 'restore'])->name('contacts.restore');
        Route::get('/devis/create/{rdvId}', [DevisController::class, 'create'])->name('devis.create');
    });

    // Admin Dashboard
    Route::middleware(['role:Admin|Super Admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('abonnements', AbonnementController::class);
        Route::get('/abonnements/active', [AbonnementController::class, 'active'])->name('abonnements.active');
    });

    // Gestion des Commissions (Freelancers and Admins)
    Route::middleware(['role:Freelancer|Admin'])->group(function () {
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
        Route::post('/commissions', [CommissionController::class, 'store'])->name('commissions.store');
        Route::put('/commissions/{commission}/approve', [CommissionController::class, 'approve'])->name('commissions.approve');
    });

    Route::resource('contacts', ContactController::class);
});

// Route pour les utilisateurs non connectés (page d'accueil ou autre)
Route::get('/', function () {
    return view('welcome');
})->name('home');

require __DIR__ . '/auth.php';
