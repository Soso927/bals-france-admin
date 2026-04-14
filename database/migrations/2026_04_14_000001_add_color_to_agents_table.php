<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Couleur personnalisée de l'agent pour la carte (format hexadécimal CSS #RRGGBB).
            // nullable() : les agents existants conserveront NULL → le JS retombe sur la couleur de région.
            // default('#94A3B8') correspond à DEFAULT_COLOR dans france-map.js.
            $table->string('color', 7)->nullable()->default('#94A3B8')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
