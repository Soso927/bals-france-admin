<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // Chaque tableau représente une ligne dans la table `regions`.
        // 'name'  = le nom officiel de la région (doit correspondre exactement
        //           aux clés utilisées dans AgentSeeder et dans REGION_MAP du JS)
        // 'zone'  = le nom de la zone commerciale affiché dans l'info-box de la carte
        // 'color' = la couleur hexadécimale pour colorier les départements sur la carte D3.js
        $regions = [
            [
                'name'  => 'Auvergne-Rhône-Alpes',
                'zone'  => 'AUVERGNE-RHÔNE-ALPES',
                'color' => '#7C3AED',
            ],
            [
                'name'  => 'Bourgogne-Franche-Comté',
                'zone'  => 'BOURGOGNE FRANCHE-COMTÉ',
                'color' => '#059669',
            ],
            [
                'name'  => 'Bretagne',
                'zone'  => 'BRETAGNE',
                'color' => '#ED1C24',
            ],
            [
                'name'  => 'Centre-Val de Loire',
                'zone'  => 'VAL DE LOIRE',
                'color' => '#CA8A04',
            ],
            [
                'name'  => 'Corse',
                'zone'  => 'MÉDITERRANÉE',
                'color' => '#0E7490',
            ],
            [
                'name'  => 'Grand Est',
                'zone'  => 'GRAND EST',
                'color' => '#FAF700',
            ],
            [
                'name'  => 'Hauts-de-France',
                'zone'  => 'HAUTS-DE-FRANCE',
                'color' => '#4F46E5',
            ],
            [
                'name'  => 'Île-de-France',
                'zone'  => 'ÎLE-DE-FRANCE',
                'color' => '#DC2626',
            ],
            [
                'name'  => 'Normandie',
                'zone'  => 'NORMANDIE',
                'color' => '#00FA08',
            ],
            [
                'name'  => 'Nouvelle-Aquitaine',
                'zone'  => 'NOUVELLE-AQUITAINE',
                'color' => '#F97316',
            ],
            [
                'name'  => 'Occitanie',
                'zone'  => 'OCCITANIE',
                'color' => '#EC4899',
            ],
            [
                'name'  => 'Pays de la Loire',
                'zone'  => 'PAYS DE LA LOIRE',
                'color' => '#A855F7',
            ],
            [
                // Point d'attention : l'apostrophe dans ce nom est une apostrophe
                // droite standard ('). Elle doit être rigoureusement identique
                // dans RegionSeeder ET dans AgentSeeder. Une différence de caractère
                // entre les deux ferait planter AgentSeeder avec l'erreur
                // "Trying to get property 'id' of null".
                'name'  => "Provence-Alpes-Côte d'Azur",
                'zone'  => 'MÉDITERRANÉE',
                'color' => '#0E7490',
            ],
        ];

        // On boucle et on insère chaque région en base.
        // Region::create() utilise le tableau $fillable du modèle
        // pour n'accepter que les colonnes autorisées.
        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}