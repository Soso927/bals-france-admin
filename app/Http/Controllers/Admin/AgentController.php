<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * AgentController — Gestion CRUD des agents commerciaux
 *
 * Ce contrôleur suit le pattern RESTful Resource de Laravel.
 * Toutes les routes sont protégées par le middleware 'admin'
 * défini dans routes/web.php.
 *
 * Architecture MVC :
 *   - Le Modèle (Agent, Region) gère les données et les règles métier
 *   - Ce Contrôleur reçoit les requêtes HTTP et orchestre les réponses
 *   - La Vue (les fichiers Blade) affiche le résultat
 */
class AgentController extends Controller
{
    /**
     * INDEX — Affiche la liste de tous les agents
     *
     * Correspond à la route GET /admin/agents
     *
     * La méthode with('region') est ce qu'on appelle l'Eager Loading.
     * Sans elle, Eloquent ferait une requête SQL séparée pour chaque
     * agent afin de récupérer sa région — c'est le problème N+1.
     * Avec with('region'), une seule requête JOIN est effectuée.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // On récupère tous les agents avec leur région associée,
        // triés par nom de région puis par nom d'agent
        $agents = Agent::with('region')
                       ->orderBy('region_id')
                       ->orderBy('nom')
                       ->get();

        return response()->json($agents);
    }

    /**
     * STORE — Crée un nouvel agent en base de données
     *
     * Correspond à la route POST /admin/agents
     *
     * La validation Laravel est la première ligne de défense.
     * Si une règle échoue, Laravel retourne automatiquement une
     * réponse 422 (Unprocessable Entity) avec les messages d'erreur,
     * sans jamais atteindre la ligne Agent::create().
     *
     * @param  Request  $request  Les données envoyées depuis le formulaire
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validation des données — toutes les règles sont vérifiées
        // côté serveur, indépendamment du JavaScript côté client.
        // C'est une couche de sécurité indispensable.
        $donnéesValidées = $request->validate([
            'region_id' => 'required|integer|exists:regions,id',
            // exists:regions,id vérifie que l'ID existe vraiment en base
            // pour éviter d'attacher un agent à une région fantôme

            'nom'    => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'agence' => 'nullable|string|max:255',
            'departement'  => 'nullable|string|max:255',
            'tel'    => 'nullable|string|max:20',
            'tel_raw'=> 'nullable|string|max:20',
        ]);

        // Agent::create() insère une ligne en base et retourne
        // l'objet Agent nouvellement créé avec son id auto-généré.
        // Pour que cela fonctionne, les champs doivent être listés
        // dans $fillable dans le modèle Agent (protection mass assignment).
        $agent = Agent::create($donnéesValidées);

        // On recharge la relation region pour que la réponse JSON
        // contienne les infos de la région, pas juste region_id
        $agent->load('region');

        // Code HTTP 201 = "Created" — convention REST pour une création réussie
        return response()->json($agent, 201);
    }

    /**
     * SHOW — Affiche un agent spécifique
     *
     * Correspond à la route GET /admin/agents/{agent}
     *
     * Laravel injecte automatiquement l'objet Agent correspondant
     * à l'ID dans l'URL grâce au Route Model Binding.
     * Si l'ID n'existe pas, Laravel retourne automatiquement une
     * erreur 404 sans que tu aies à l'écrire toi-même.
     *
     * @param  Agent  $agent  L'agent injecté automatiquement par Laravel
     * @return JsonResponse
     */
    public function show(Agent $agent): JsonResponse
    {
        // load() est l'équivalent de with() mais sur un objet déjà récupéré
        $agent->load('region');

        return response()->json($agent);
    }

    /**
     * UPDATE — Met à jour un agent existant
     *
     * Correspond à la route PUT ou PATCH /admin/agents/{agent}
     *
     * La différence entre PUT et PATCH :
     *   - PUT remplace l'objet entier (tous les champs requis)
     *   - PATCH modifie partiellement (seuls les champs envoyés)
     * On utilise 'sometimes' pour supporter les deux cas.
     *
     * @param  Request  $request
     * @param  Agent    $agent    L'agent à modifier (injecté par Laravel)
     * @return JsonResponse
     */
    public function update(Request $request, Agent $agent): JsonResponse
    {
        // 'sometimes' signifie : "valide ce champ seulement s'il est présent
        // dans la requête". Cela permet les mises à jour partielles (PATCH).
        $donnéesValidées = $request->validate([
            'region_id' => 'sometimes|integer|exists:regions,id',
            'nom'    => 'sometimes|required|string|max:255',
            'email'  => 'sometimes|required|email|max:255',
            'agence' => 'nullable|string|max:255',
            'departement'  => 'nullable|string|max:255',
            'tel'    => 'nullable|string|max:20',
            'tel_raw'=> 'nullable|string|max:20',
        ]);

        // update() modifie uniquement les champs présents dans $donnéesValidées
        $agent->update($donnéesValidées);

        $agent->load('region');

        // Code HTTP 200 = "OK" — la convention pour une mise à jour réussie
        return response()->json($agent, 200);
    }

    /**
     * DESTROY — Supprime un agent
     *
     * Correspond à la route DELETE /admin/agents/{agent}
     *
     * @param  Agent  $agent  L'agent à supprimer (injecté par Laravel)
     * @return JsonResponse
     */
    public function destroy(Agent $agent): JsonResponse
    {
        // On sauvegarde le nom avant suppression pour le message de retour
        $nomAgent = $agent->nom;

        // delete() exécute un DELETE SQL et retourne true/false
        $agent->delete();

        // Code HTTP 200 avec un message de confirmation
        return response()->json([
            'message' => "L'agent {$nomAgent} a été supprimé avec succès."
        ], 200);
    }
}