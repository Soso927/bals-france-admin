<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
	public function index(): JsonResponse
	{
        // On charge toutes les régions avec leurs agents en une seule requête
        // grâce au "eager loading" (with) — évite le problème N+1
        $regions = Region::with('agents')->get();

        // On transforme la collection en un objet indexé par nom de région,
        // exactement comme l'ancien objet CONTACTS en JavaScript
        $formatted = $regions->mapWithKeys(function ($region) {
            return [$region->name => $region->toMapFormat()];
        });

        // Cache de 5 minutes : la carte est publique, les données changent peu
        return response()->json($formatted)->header('Cache-Control', 'max-age=300');
	}
}
