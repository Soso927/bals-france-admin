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
        User::updateOrCreate(
            ['email' => 'admin@bals-france.fr'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('admin1234'),
                'is_admin' => true,
            ]
        );
    }
}
