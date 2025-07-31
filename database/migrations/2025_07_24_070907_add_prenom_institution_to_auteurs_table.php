<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('auteurs', function (Blueprint $table) {
            $table->string('prenom')->nullable()->after('nom');
            $table->string('institution')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auteurs', function (Blueprint $table) {
            $table->dropColumn(['prenom', 'institution']);
        });
    }
};
