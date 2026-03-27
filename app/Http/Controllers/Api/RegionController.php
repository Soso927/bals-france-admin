<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

/**
 * RegionController — Lecture des régions pour le dashboard
 *
 * Ce contrôleur est volontairement limité à la lecture (index et show).
 * Les régions correspondent aux 13 régions françaises et ne sont pas
 * destinées à être créées ou supprimées depuis l'interface.
 */
class RegionController extends Controller
{
    /**
     * INDEX — Retourne toutes les régions avec leurs agents
     *
     * C'est cette route que ton JavaScript appellera pour construire
     * l'affichage complet du dashboard et de la carte interactive.
     * Elle remplace directement l'ancien localStorage.getItem('bals_contacts').
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // withCount('agents') ajoute automatiquement un champ
        // 'agents_count' à chaque région dans la réponse JSON.
        // C'est utile pour afficher "3 agents" sans charger tous les agents.
        $regions = Region::with('agents')
                         ->withCount('agents')
                         ->orderBy('nom')
                         ->get();

        return response()->json($regions);
    }

    /**
     * SHOW — Retourne une région avec ses agents
     *
     * Correspond à la route GET /admin/regions/{region}
     *
     * @param  Region  $region
     * @return JsonResponse
     */
    public function show(Region $region): JsonResponse
    {
        $region->load('agents');

        return response()->json($region);
    }
}