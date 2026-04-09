<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        // On vide la table agents avant d'insérer
        // pour éviter les doublons si on relance le seeder
        Agent::truncate();

        // On lit le fichier JSON qu'on a préparé
        // File::get() lit le contenu brut du fichier
        // json_decode() le convertit en tableau PHP (true = tableau associatif)
        $json = File::get(resource_path('data/agent.json'));
        $agents = json_decode($json, true);

        // On charge toutes les régions et on les indexe par leur nom
        // keyBy('nom') transforme le tableau de régions en tableau associatif :
        // ['Normandie' => Region{id:1, ...}, 'Bretagne' => Region{id:2, ...}, ...]
        // Cela nous permet ensuite d'écrire $regions['Normandie']->id
        // au lieu de chercher l'id à la main
        $regions = Region::all()->keyBy('nom');

        // On boucle sur chaque agent du fichier JSON
        foreach ($agents as $agentData) {

            // On vérifie que la région existe bien en base
            // pour éviter une erreur si un nom est mal orthographié dans le JSON
            $regionNom = $agentData['region_nom'];

            if (!isset($regions[$regionNom])) {
                // Si la région n'existe pas, on avertit et on passe au suivant
                $this->command->warn("Région introuvable : {$regionNom} — agent ignoré.");
                continue;
            }

            // On crée l'agent en base de données
            // On fait la correspondance entre les clés du JSON et les colonnes SQL :
            // 'departement' (JSON) → 'departement' (colonne MySQL)
            // 'region_nom' (JSON) → 'region_id' (clé étrangère MySQL)
            Agent::create([
                'region_id' => $regions[$regionNom]->id,
                'agence'    => $agentData['agence'],
                'nom'       => $agentData['nom'],
                'departement'     => $agentData['departement'], // correspondance JSON → SQL
                'tel'       => $agentData['tel'],
                'tel_raw'   => $agentData['tel_raw'],
                'email'     => $agentData['email'],
            ]);
        }

        $this->command->info('AgentSeeder terminé — ' . count($agents) . ' agents insérés.');
    }
}