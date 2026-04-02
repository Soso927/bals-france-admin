<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

/**
 * RegionSeeder — Peuple la table regions avec les 13 régions françaises.
 *
 * Ces données correspondent exactement aux clés de l'ancien objet
 * DONNEES_PAR_DEFAUT qui était en dur dans le JavaScript.
 * On les migre ici pour qu'elles vivent côté serveur dans MySQL.
 */
class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // On définit les régions sous forme de tableau pour pouvoir
        // les insérer en une seule opération avec insert().
        // Chaque entrée contient 'nom' (clé de l'ancien JS) et 'zone'
        // (valeur du champ 'zone' dans l'ancien objet).
        $regions = [
            ['nom' => 'Normandie',                    'zone' => 'NORMANDIE'],
            ['nom' => 'Bretagne',                     'zone' => 'BRETAGNE'],
            ['nom' => 'Pays de la Loire',             'zone' => 'PAYS DE LA LOIRE'],
            ['nom' => 'Centre-Val de Loire',          'zone' => 'VAL DE LOIRE'],
            ['nom' => 'Île-de-France',                'zone' => 'ÎLE-DE-FRANCE'],
            ['nom' => 'Hauts-de-France',              'zone' => 'HAUTS-DE-FRANCE'],
            ['nom' => 'Grand Est',                    'zone' => 'GRAND EST'],
            ['nom' => 'Bourgogne-Franche-Comté',      'zone' => 'BOURGOGNE FRANCHE-COMTÉ'],
            ['nom' => 'Auvergne-Rhône-Alpes',         'zone' => 'AUVERGNE-RHÔNE-ALPES'],
            ['nom' => 'Nouvelle-Aquitaine',           'zone' => 'NOUVELLE-AQUITAINE'],
            ['nom' => 'Occitanie',                    'zone' => 'OCCITANIE'],
            ['nom' => "Provence-Alpes-Côte d'Azur",  'zone' => 'MÉDITERRANÉE'],
            ['nom' => 'Corse',                        'zone' => 'MÉDITERRANÉE'],
        ];

        // firstOrCreate évite les doublons si tu relances le seeder :
        // il vérifie d'abord si la région existe avant de l'insérer.
        foreach ($regions as $regionData) {
            Region::firstOrCreate(
                ['nom' => $regionData['nom']],
                ['zone' => $regionData['zone']]
            );
        }
    }
}