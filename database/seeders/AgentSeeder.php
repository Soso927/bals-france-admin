<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Region;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        // On charge toutes les régions et on les indexe par leur nom.
        // Grâce à keyBy('name'), on peut écrire $regions['Normandie']->id
        // plutôt que de deviner quel id MySQL a attribué à chaque région.
        // Cette technique rend le seeder robuste : il fonctionnera toujours
        // correctement même après un migrate:fresh qui réassigne les ids.
        $regions = Region::all()->keyBy('name');

        $agents = [

            // ══════════════════════════════════════════════════
            // NORMANDIE — 1 agent
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Normandie']->id,
                'agence'    => null,
                'nom'       => 'Vincent CARPENTIER',
                'depts'     => 'Dépt. 14, 27, 50, 61, 76',
                'tel'       => '06 27 32 49 23',
                'tel_raw'   => '+330627324923',
                'email'     => 'vincent.carpentier85@sfr.fr',
                'order'     => 0,
            ],

            // ══════════════════════════════════════════════════
            // BRETAGNE — 1 agent
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Bretagne']->id,
                'agence'    => 'AGENCE BONDUELLE',
                'nom'       => 'Isabelle ESPELLE',
                'depts'     => 'Dépt. 22, 29, 35, 56',
                'tel'       => '02 40 09 77 95',
                'tel_raw'   => '+330240097795',
                'email'     => 'contact@agence-bonduelle.com',
                'order'     => 0,
            ],

            // ══════════════════════════════════════════════════
            // PAYS DE LA LOIRE — 1 agent
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Pays de la Loire']->id,
                'agence'    => 'AGENCE BONDUELLE',
                'nom'       => 'Isabelle ESPELLE',
                'depts'     => 'Dépt. 44, 49, 53, 72, 79, 85',
                'tel'       => '02 40 09 77 95',
                'tel_raw'   => '+330240097795',
                'email'     => 'contact@agence-bonduelle.com',
                'order'     => 0,
            ],

            // ══════════════════════════════════════════════════
            // CENTRE-VAL DE LOIRE — 1 agent
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Centre-Val de Loire']->id,
                'agence'    => null,
                'nom'       => 'Yann GUYADER',
                'depts'     => 'Dépt. 18, 28, 36, 37, 41, 45, 86',
                'tel'       => '06 02 19 82 90',
                'tel_raw'   => '+330602198290',
                'email'     => 'yguyader@gstec.fr',
                'order'     => 0,
            ],

            // ══════════════════════════════════════════════════
            // ÎLE-DE-FRANCE — 3 agents (même agence, départements différents)
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Île-de-France']->id,
                'agence'    => 'AGENCE DUMAS',
                'nom'       => 'Alexis ANDRADE SILVA',
                'depts'     => 'Dépt. 75, 78, 92',
                'tel'       => '06 33 37 30 17',
                'tel_raw'   => '+330633373017',
                'email'     => 'a.andradesilva@agencedumas.net',
                'order'     => 0,
            ],
            [
                'region_id' => $regions['Île-de-France']->id,
                'agence'    => 'AGENCE DUMAS',
                'nom'       => 'Arnaud JOUSSELIN',
                'depts'     => 'Dépt. 93, 95',
                'tel'       => '06 50 98 23 67',
                'tel_raw'   => '+330650982367',
                'email'     => 'a.jousselin@agencedumas.net',
                'order'     => 10,
            ],
            [
                'region_id' => $regions['Île-de-France']->id,
                'agence'    => 'AGENCE DUMAS',
                'nom'       => 'Adrien DUMAS',
                'depts'     => 'Dépt. 77, 91, 94',
                'tel'       => '06 33 57 21 38',
                'tel_raw'   => '+330633572138',
                'email'     => 'a.dumas@agencedumas.net',
                'order'     => 20,
            ],

            // ══════════════════════════════════════════════════
            // HAUTS-DE-FRANCE — 2 agents, 2 agences différentes
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Hauts-de-France']->id,
                'agence'    => 'AGENCE BESSA',
                'nom'       => 'Francis BESSA',
                'depts'     => 'Dépt. 59, 62, 80',
                'tel'       => '06 09 62 92 30',
                'tel_raw'   => '+330609629230',
                'email'     => 'Francis.bessa@agencebessa.fr',
                'order'     => 0,
            ],
            [
                'region_id' => $regions['Hauts-de-France']->id,
                'agence'    => 'AGENCE PICHAMPARDENNAISE',
                'nom'       => 'Angéline FAUCHART-PETIT',
                'depts'     => 'Dépt. 02, 60',
                'tel'       => '06 62 39 11 93',
                'tel_raw'   => '+330662391193',
                'email'     => 'agence.angeline@orange.fr',
                'order'     => 10,
            ],

            // ══════════════════════════════════════════════════
            // GRAND EST — 2 agents, 2 agences différentes
            // Angéline FAUCHART-PETIT couvre aussi Hauts-de-France.
            // Ce n'est pas un doublon : c'est la même personne sur deux
            // territoires différents, donc deux entrées distinctes en base.
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Grand Est']->id,
                'agence'    => 'AGENCE VIERLING',
                'nom'       => 'Christian VIERLING',
                'depts'     => 'Dépt. 54, 55, 57, 67, 68, 88, 90',
                'tel'       => '06 09 48 66 91',
                'tel_raw'   => '+330609486691',
                'email'     => 'contact@agencevierling.fr',
                'order'     => 0,
            ],
            [
                'region_id' => $regions['Grand Est']->id,
                'agence'    => 'AGENCE PICHAMPARDENNAISE',
                'nom'       => 'Angéline FAUCHART-PETIT',
                'depts'     => 'Dépt. 08, 10, 51, 52',
                'tel'       => '06 62 39 11 93',
                'tel_raw'   => '+330662391193',
                'email'     => 'agence.angeline@orange.fr',
                'order'     => 10,
            ],

            // ══════════════════════════════════════════════════
            // BOURGOGNE-FRANCHE-COMTÉ — 1 agent
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Bourgogne-Franche-Comté']->id,
                'agence'    => 'FANJOUX',
                'nom'       => 'Raphaël LEGRAND',
                'depts'     => 'Dépt. 21, 25, 39, 58, 70, 71, 89',
                'tel'       => '06 12 22 34 16',
                'tel_raw'   => '+330612223416',
                'email'     => 'Raphael.legrand@fanjouxdiffusion.com',
                'order'     => 0,
            ],

            // ══════════════════════════════════════════════════
            // AUVERGNE-RHÔNE-ALPES — 4 agents, 2 agences
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Auvergne-Rhône-Alpes']->id,
                'agence'    => 'MONIER',
                'nom'       => 'Ghislain MONIER',
                'depts'     => 'Dépt. 03, 15, 43, 63',
                'tel'       => '06 78 98 74 30',
                'tel_raw'   => '+330678987430',
                'email'     => 'contact@agencemonier.com',
                'order'     => 0,
            ],
            [
                'region_id' => $regions['Auvergne-Rhône-Alpes']->id,
                'agence'    => 'AGENCE XPE XPRO ELEC',
                'nom'       => 'Lionel AUCLAIR',
                'depts'     => 'Dépt. 07, 26, 42',
                'tel'       => '07 85 23 64 23',
                'tel_raw'   => '+330785236423',
                'email'     => 'lauclair@xpe-france.fr',
                'order'     => 10,
            ],
            [
                'region_id' => $regions['Auvergne-Rhône-Alpes']->id,
                'agence'    => 'AGENCE XPE XPRO ELEC',
                'nom'       => 'Nicolas CHARPENTIER',
                'depts'     => 'Dépt. 01, 73, 74',
                'tel'       => '06 08 62 00 39',
                'tel_raw'   => '+330608620039',
                'email'     => 'ncharpentier@xpe-france.com',
                'order'     => 20,
            ],
            [
                'region_id' => $regions['Auvergne-Rhône-Alpes']->id,
                'agence'    => 'AGENCE XPE XPRO ELEC',
                'nom'       => 'Olivier REYNAUD',
                'depts'     => 'Dépt. 38, 69',
                'tel'       => '06 80 08 25 26',
                'tel_raw'   => '+330680082526',
                'email'     => 'commercial@xpe-france.com',
                'order'     => 30,
            ],

            // ══════════════════════════════════════════════════
            // NOUVELLE-AQUITAINE — 3 agents, 2 agences
            // Ghislain MONIER couvre aussi Auvergne-Rhône-Alpes,
            // même logique que pour Angéline FAUCHART-PETIT.
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Nouvelle-Aquitaine']->id,
                'agence'    => 'MONIER',
                'nom'       => 'Ghislain MONIER',
                'depts'     => 'Dépt. 19, 23, 87',
                'tel'       => '06 78 98 74 30',
                'tel_raw'   => '+330678987430',
                'email'     => 'contact@agencemonier.com',
                'order'     => 0,
            ],
            [
                'region_id' => $regions['Nouvelle-Aquitaine']->id,
                'agence'    => 'RMEE',
                'nom'       => 'Christophe NIETRZEBA',
                'depts'     => 'Dépt. 16, 17, 24',
                'tel'       => '06 80 46 93 93',
                'tel_raw'   => '+330680469393',
                'email'     => 'c.nietrzeba@rmee.fr',
                'order'     => 10,
            ],
            [
                'region_id' => $regions['Nouvelle-Aquitaine']->id,
                'agence'    => 'RMEE',
                'nom'       => 'Jean-Christophe SEBILE',
                'depts'     => 'Dépt. 33, 40, 47, 64',
                'tel'       => '06 86 16 63 64',
                'tel_raw'   => '+330686166364',
                'email'     => 'jc.sebile@rmee.fr',
                'order'     => 20,
            ],

            // ══════════════════════════════════════════════════
            // OCCITANIE — 3 agents, 2 agences
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Occitanie']->id,
                'agence'    => 'REPELEC',
                'nom'       => 'Cédric RICAUD',
                'depts'     => 'Dépt. 09, 11, 32, 46, 65, 82',
                'tel'       => '06 33 98 59 18',
                'tel_raw'   => '+330633985918',
                'email'     => 'c.ricaud@repelec.fr',
                'order'     => 0,
            ],
            [
                'region_id' => $regions['Occitanie']->id,
                'agence'    => 'REPELEC',
                'nom'       => 'Sébastien LIENARD',
                'depts'     => 'Dépt. 12, 31, 48, 66, 81',
                'tel'       => '06 78 22 54 30',
                'tel_raw'   => '+330678225430',
                'email'     => 's.lienard@repelec.fr',
                'order'     => 10,
            ],
            [
                'region_id' => $regions['Occitanie']->id,
                'agence'    => 'AGENCE RENAUDI',
                'nom'       => 'Fabien RENAUDI',
                'depts'     => 'Dépt. 30, 34',
                'tel'       => '06 29 44 69 94',
                'tel_raw'   => '+330629446994',
                'email'     => 'contact@agencerenaudi.fr',
                'order'     => 20,
            ],

            // ══════════════════════════════════════════════════
            // PROVENCE-ALPES-CÔTE D'AZUR — 1 agent
            // ══════════════════════════════════════════════════
            [
                // L'apostrophe ici doit être identique à celle de RegionSeeder.
                'region_id' => $regions["Provence-Alpes-Côte d'Azur"]->id,
                'agence'    => 'AGENCE RENAUDI',
                'nom'       => 'Fabien RENAUDI',
                'depts'     => 'Dépt. 04, 05, 06, 13, 83, 84',
                'tel'       => '06 29 44 69 94',
                'tel_raw'   => '+330629446994',
                'email'     => 'contact@agencerenaudi.fr',
                'order'     => 0,
            ],

            // ══════════════════════════════════════════════════
            // CORSE — 1 agent
            // ══════════════════════════════════════════════════
            [
                'region_id' => $regions['Corse']->id,
                'agence'    => 'AGENCE RENAUDI',
                'nom'       => 'Fabien RENAUDI',
                'depts'     => 'Dépt. 2A, 2B',
                'tel'       => '06 29 44 69 94',
                'tel_raw'   => '+330629446994',
                'email'     => 'contact@agencerenaudi.fr',
                'order'     => 0,
            ],
        ];

        foreach ($agents as $agent) {
            Agent::create($agent);
        }
    }
}