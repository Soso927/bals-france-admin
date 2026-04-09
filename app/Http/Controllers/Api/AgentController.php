<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Contrôleur API pour la gestion des agents commerciaux.
 *
 * Ce contrôleur expose les 5 opérations CRUD via des endpoints REST :
 *   GET    /api/agents         → index()   : lister tous les agents par région
 *   POST   /api/agents         → store()   : créer un nouvel agent
 *   PUT    /api/agents/{id}    → update()  : modifier un agent existant
 *   DELETE /api/agents/{id}    → destroy() : supprimer un agent
 *
 * Toutes les réponses sont au format JSON, ce qui permet au JavaScript
 * côté client de les lire facilement (fetch API).
 *
 * La sécurité est gérée par le middleware 'auth:sanctum' déclaré
 * dans les routes — seul un admin authentifié peut modifier les données.
 */
class AgentController extends Controller
{
    /**
     * INDEX — Retourne toutes les régions avec leurs agents.
     *
     * C'est l'endpoint appelé au chargement de la carte interactive ET
     * du tableau de bord admin. Il remplace complètement localStorage.
     *
     * La méthode with('agents') est ce qu'on appelle du "eager loading" :
     * Eloquent fait UNE seule requête SQL avec une jointure, au lieu de
     * faire N requêtes (une par région). C'est bien plus performant.
     *
     * Exemple de réponse JSON :
     * {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "nom": "Île-de-France",
     *       "zone": "ÎLE-DE-FRANCE",
     *       "agents": [ { "id": 1, "nom": "Alexis ANDRADE SILVA", ... } ]
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        // On charge toutes les régions avec leurs agents en une seule requête.
        $regions = Region::with('agents')->orderBy('nom')->get();

        return response()->json([
            'success' => true,
            'data'    => $regions,
        ]);
    }

    /**
     * STORE — Crée un nouvel agent en base de données.
     *
     * Reçoit les données via une requête POST (envoyées par le formulaire
     * d'ajout du dashboard). Valide les données AVANT de les enregistrer,
     * ce qui protège la base contre les données malformées ou malveillantes.
     *
     * La validation côté serveur est indispensable même si tu valides
     * déjà côté client (JS) : un utilisateur malveillant peut contourner
     * la validation JavaScript en envoyant des requêtes directement.
     *
     * @param Request $request  Les données envoyées par le client (nom, email, etc.)
     */
    public function store(Request $request): JsonResponse
    {
        // Règles de validation : on définit ce qui est obligatoire,
        // le type attendu, et la longueur maximale pour chaque champ.
        $validator = Validator::make($request->all(), [
            'region_id'   => 'required|integer|exists:regions,id', // La région doit exister en BDD
            'nom'         => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'agence'      => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:500',
            'tel'         => 'nullable|string|max:50',
            'tel_raw'     => 'nullable|string|max:50',
        ]);

        // Si la validation échoue, on retourne les erreurs avec le code HTTP 422
        // (Unprocessable Entity = données reçues mais invalides sémantiquement).
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // On crée l'agent en base. validated() retourne uniquement les champs
        // qui ont passé les règles de validation, ce qui évite d'insérer
        // des données inattendues (protection contre le mass assignment).
        $agent = Agent::create($validator->validated());

        // Code HTTP 201 = "Created" : convention REST pour signaler
        // qu'une ressource a bien été créée.
        return response()->json([
            'success' => true,
            'message' => 'Agent créé avec succès.',
            'data'    => $agent,
        ], 201);
    }

    /**
     * UPDATE — Modifie un agent existant.
     *
     * Laravel retrouve automatiquement l'agent grâce à son ID dans l'URL
     * grâce au mécanisme de "Route Model Binding" : au lieu d'écrire
     * Agent::find($id), on déclare Agent $agent en paramètre et Laravel
     * fait la requête tout seul. Si l'agent n'existe pas, il retourne
     * automatiquement une erreur 404.
     *
     * @param Request $request  Les nouvelles données
     * @param Agent   $agent    L'agent à modifier (injecté par Laravel via l'ID dans l'URL)
     */
    public function update(Request $request, Agent $agent): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'region_id'   => 'sometimes|integer|exists:regions,id',
            'nom'         => 'sometimes|required|string|max:255',
            'email'       => 'sometimes|required|email|max:255',
            'agence'      => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:500',
            'tel'         => 'nullable|string|max:50',
            'tel_raw'     => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // update() modifie uniquement les colonnes passées, pas toute la ligne.
        $agent->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Agent modifié avec succès.',
            'data'    => $agent->fresh(), // fresh() recharge l'agent depuis la BDD
        ]);
    }

    /**
     * DESTROY — Supprime un agent.
     *
     * Là encore, le Route Model Binding de Laravel trouve l'agent par son ID
     * et retourne 404 automatiquement si il n'existe pas.
     *
     * @param Agent $agent  L'agent à supprimer
     */
    public function destroy(Agent $agent): JsonResponse
    {
        $agent->delete();

        // Code HTTP 200 avec un message de confirmation.
        // Certaines API retournent 204 (No Content) mais on préfère
        // retourner un message pour que le JS puisse afficher un toast.
        return response()->json([
            'success' => true,
            'message' => 'Agent supprimé avec succès.',
        ]);
    }
}