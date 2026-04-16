<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('agents', 'color')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->string('color', 7)->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};