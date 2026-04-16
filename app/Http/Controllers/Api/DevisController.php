<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    /**
     * Enregistre une nouvelle demande de devis depuis le configurateur public.
     * Route publique (pas d'authentification requise).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type_coffret'         => 'required|string|max:100',
            'distributeur'         => 'nullable|string|max:255',
            'contact'              => 'nullable|string|max:255',
            'installateur'         => 'nullable|string|max:255',
            'contact_installateur' => 'nullable|string|max:255',
            'affaire'              => 'nullable|string|max:255',
            'email'                => 'nullable|email|max:255',
            'telephone'            => 'nullable|string|max:50',
            'donnees'              => 'nullable|array',
            'observations'         => 'nullable|string',
        ]);

        $validated['statut'] = 'nouveau';
        
        $devis = Devis::create($validated);

        return response()->json(['success' => true, 'id' => $devis->id], 201);
    }
}