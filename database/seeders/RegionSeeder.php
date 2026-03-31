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
            ['name' => 'Normandie',                    'zone' => 'NORMANDIE'],
            ['name' => 'Bretagne',                     'zone' => 'BRETAGNE'],
            ['name' => 'Pays de la Loire',             'zone' => 'PAYS DE LA LOIRE'],
            ['name' => 'Centre-Val de Loire',          'zone' => 'VAL DE LOIRE'],
            ['name' => 'Île-de-France',                'zone' => 'ÎLE-DE-FRANCE'],
            ['name' => 'Hauts-de-France',              'zone' => 'HAUTS-DE-FRANCE'],
            ['name' => 'Grand Est',                    'zone' => 'GRAND EST'],
            ['name' => 'Bourgogne-Franche-Comté',      'zone' => 'BOURGOGNE FRANCHE-COMTÉ'],
            ['name' => 'Auvergne-Rhône-Alpes',         'zone' => 'AUVERGNE-RHÔNE-ALPES'],
            ['name' => 'Nouvelle-Aquitaine',           'zone' => 'NOUVELLE-AQUITAINE'],
            ['name' => 'Occitanie',                    'zone' => 'OCCITANIE'],
            ['name' => "Provence-Alpes-Côte d'Azur",  'zone' => 'MÉDITERRANÉE'],
            ['name' => 'Corse',                        'zone' => 'MÉDITERRANÉE'],
        ];

        // firstOrCreate évite les doublons si tu relances le seeder :
        // il vérifie d'abord si la région existe avant de l'insérer.
        foreach ($regions as $regionData) {
            Region::firstOrCreate(
                ['name' => $regionData['name']],
                ['zone' => $regionData['zone']]
            );
        }
    }
}