<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])->name('authenticate');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::view('dashboard', 'admin.dashboard')->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');
    });
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

withMiddleware(function (Middleware $middleware){
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
});

// ══════════════════════════════════════════════════════════
// ROUTES PUBLIQUES ADMIN — connexion/déconnexion
// Accessibles sans être connecté
// ══════════════════════════════════════════════════════════
Route::prefix('admin')->group(function () {

    // GET /admin → affiche le formulaire de connexion
    Route::get('/', [AdminAuthController::class, 'showLogin'])
        ->name('admin.login');

    // POST /admin → traite le formulaire de connexion
    Route::post('/', [AdminAuthController::class, 'login'])
        ->name('admin.login.submit');

    // POST /admin/logout → déconnecte l'admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

    // ══════════════════════════════════════════════════════
    // ROUTES PROTÉGÉES — accessibles uniquement aux admins
    // Le middleware 'admin' bloque quiconque n'est pas admin
    // ══════════════════════════════════════════════════════
    Route::middleware('admin')->group(function () {

        // GET /admin/dashboard → tableau de bord principal
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });


require __DIR__.'/auth.php';
});