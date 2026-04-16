<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();

            // Type de configurateur
            $table->string('type_coffret', 100);

            // Informations de contact
            $table->string('distributeur', 255)->nullable();
            $table->string('contact', 255)->nullable();
            $table->string('installateur', 255)->nullable();
            $table->string('contact_installateur', 255)->nullable();
            $table->string('affaire', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telephone', 50)->nullable();

            // Données techniques (montage, materiau, ip, prises, protections)
            $table->json('donnees')->nullable();

            // Observations libres
            $table->text('observations')->nullable();

            // Suivi de la demande : nouveau / lu / traité
            $table->string('statut', 20)->default('nouveau');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};