<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // L'ordre ici est non négociable.
        // AdminSeeder en premier car il ne dépend de rien.
        // RegionSeeder en second car les agents ont besoin des régions.
        // AgentSeeder en dernier car il référence les ids des régions.
        $this->call([
            AdminSeeder::class,
            RegionSeeder::class,
            AgentSeeder::class,
        ]);
    }
}