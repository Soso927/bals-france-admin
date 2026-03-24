<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Adminseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@bals-france.fr',

            // Hash::make() chiffre le mot de passe avec bcrypt.
            // on ne stocke JAMAIS un mot de passe en clair en base
            // Laravel sait automatiquement comparer avec ce hash avec 
            // ce que l'admin tapera dans le formulaire de connexion. 
            'password' => Hash::make('admin1234'),
            
            // true = cet utilisateur a accès au back-office. 
            // Par défault (false), un utilisateur est un visiteur normal
            'is_admin' => true, 
        ]);
    }
}
