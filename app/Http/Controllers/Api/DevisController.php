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
    public function soumettre(Request $request)
{
    // Validation des fichiers
    $request->validate([
        'fichiers'   => ['nullable', 'array', 'max:5'],       // max 5 fichiers
        'fichiers.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10 Mo chacun
    ]);

    $cheminsFichiers = [];

    if ($request->hasFile('fichiers')) {
        foreach ($request->file('fichiers') as $fichier) {
            // Stocke dans storage/app/public/devis/
            // Le nom est auto-généré pour éviter les collisions
            $chemin = $fichier->store('devis', 'public');
            $cheminsFichiers[] = $chemin;
        }
    }

    // ... reste de ta logique (création du devis, envoi email, etc.)
    // $cheminsFichiers contient les chemins relatifs pour les stocker en BDD
}
}