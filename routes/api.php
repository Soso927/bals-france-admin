<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\DevisController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API — Réseau commercial Bals France
|--------------------------------------------------------------------------
|
| Ces routes sont automatiquement préfixées par "/api" par Laravel.
| Elles retournent toutes du JSON, jamais du HTML.
|
| La route publique (GET /api/agents) est accessible sans authentification
| car la carte interactive est visible par tous les visiteurs.
|
| Les routes de modification (POST, PUT, DELETE) sont protégées par le
| middleware 'auth:sanctum' : seul un admin connecté peut les appeler.
| Si quelqu'un tente d'y accéder sans être connecté, Laravel retourne
| automatiquement une erreur 401 (Unauthorized).
|
*/

// ── Route PUBLIQUE : lecture seule ─────────────────────────────────────────
// Accessible par tous les visiteurs pour alimenter la carte interactive.
Route::get('/agents', [AgentController::class, 'index']);

// ── Route PUBLIQUE : soumission d'une demande de devis ─────────────────────
// Appelée par le bouton "Envoyer" des configurateurs (sans authentification).
Route::post('/devis', [DevisController::class, 'store']);

// ── Routes PROTÉGÉES : modifications réservées à l'admin connecté ──────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/agents',       [AgentController::class, 'store']);
    Route::put('/agents/{agent}', [AgentController::class, 'update']);
    Route::delete('/agents/{agent}', [AgentController::class, 'destroy']);
});