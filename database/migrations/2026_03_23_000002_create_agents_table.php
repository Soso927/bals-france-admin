<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();

            // Clé étrangère : chaque agent appartient à une région
            // C'est ici que tu démontres la relation entre deux tables au jury
            $table->foreignId('region_id')
                  ->constrained('regions')       // FOREIGN KEY vers regions.id
                  ->cascadeOnDelete();           // Si on supprime une région, ses agents sont supprimés

            $table->string('agence', 150)->nullable(); // Optionnel (certains agents n'ont pas d'agence)
            $table->string('nom', 150);
            $table->string('departement', 255);          // Ex: "Dépt. 75, 78, 92"
            $table->string('tel', 20);             // Numéro formaté pour l'affichage
            $table->string('tel_raw', 20);         // Numéro brut pour le lien tel:+33...
            $table->string('email', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};