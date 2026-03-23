<?php

use App\Http\Controllers\Api\RegionController;
use Illuminate\Support\Facades\Route;

Route::get('regions', [RegionController::class, 'index'])->name('api.regions.index');
// Route publique - pas besoin d'authentification pour lire les agents
Route::get('/regions',[App\Http\Controllers\Api\RegionController::class, 'index']);