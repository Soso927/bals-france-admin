<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() = ce qu'on fait quand on "monte" la migration (créer la table)
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();                           // Clé primaire auto-incrémentée
            $table->string('nom', 100);             // Ex: "Île-de-France"
            $table->string('zone', 100);            // Ex: "ÎLE-DE-FRANCE" (affiché sur la carte)
            $table->string('couleur', 7)->default('#94A3B8'); // Code hex pour la carte SVG
            $table->timestamps();                   // created_at et updated_at automatiques
        });
    }

    // down() = ce qu'on fait si on veut annuler (supprimer la table)
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};