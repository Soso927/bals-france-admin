<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// ══════════════════════════════════════════════════════════
// PAGE D'ACCUEIL — site public
// ══════════════════════════════════════════════════════════
Route::get('/', function () {
    $stats = [
        'totalAgents'  => \App\Models\Agent::count(),
        'totalRegions' => \App\Models\Region::count(),
        'totalAgences' => \App\Models\Agent::whereNotNull('agence')
                            ->distinct('agence')
                            ->count('agence'),
    ];
    return view('welcome', compact('stats'));
})->name('home');

// ══════════════════════════════════════════════════════════
// CARTE INTERACTIVE — accessible à tous les visiteurs
// ══════════════════════════════════════════════════════════
Route::get('/france-map', function () {
    return view('france-map');
});

// ══════════════════════════════════════════════════════════
// AUTHENTIFICATION — gérée entièrement par Livewire Volt
// Le fichier auth.php est généré automatiquement par le
// starter kit. Il contient les routes /login, /register,
// /logout, etc. Tu n'as rien à y toucher.
// ══════════════════════════════════════════════════════════
require __DIR__.'/auth.php';

// ══════════════════════════════════════════════════════════
// ZONE ADMIN — protégée par deux middlewares cumulés :
//   - 'auth'  : vérifie que l'utilisateur est connecté
//   - 'admin' : vérifie que is_admin = true dans la BDD
// Si l'une des deux conditions échoue, Laravel redirige
// automatiquement vers /login
// ══════════════════════════════════════════════════════════
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Tableau de bord principal
        Route::get('dashboard', function () {
            $stats = [
                'totalAgents'  => \App\Models\Agent::count(),
                'totalRegions' => \App\Models\Region::count(),
                'totalAgences' => \App\Models\Agent::whereNotNull('agence')
                                    ->distinct('agence')
                                    ->count('agence'),
            ];
            return view('admin.dashboard', compact('stats'));
        })->name('dashboard');

    });
