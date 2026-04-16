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
// CONFIGURATEURS DE DEVIS — pages publiques
// ══════════════════════════════════════════════════════════
Route::prefix('configurateur')->name('configurateur.')->group(function () {
    // Cette ligne permet de répondre à l'URL "/configurateur"
    Route::get('/', fn() => view('configurateur.index'))->name('index');
    Route::get('chantier',           fn() => view('configurateur.chantier'))->name('chantier');
    Route::get('industrie',          fn() => view('configurateur.industrie'))->name('industrie');
    Route::get('prise-industrielle', fn() => view('configurateur.prise-industrielle'))->name('prise-industrielle');
    Route::get('etage',              fn() => view('configurateur.etage'))->name('etage');
    Route::get('evenementiel',       fn() => view('configurateur.evenementiel'))->name('evenementiel');
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

        // Demandes de devis
        Route::get('devis', function () {
            $stats = [
                'nouveaux' => \App\Models\Devis::where('statut', 'nouveau')->count(),
                'lus'      => \App\Models\Devis::where('statut', 'lu')->count(),
                'traites'  => \App\Models\Devis::where('statut', 'traité')->count(),
            ];
            return view('admin.devis', compact('stats'));
        })->name('devis');

    });
