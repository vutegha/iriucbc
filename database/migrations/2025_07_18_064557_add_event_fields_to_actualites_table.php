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
        Schema::table('actualites', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('actualites', 'date_evenement')) {
                $table->datetime('date_evenement')->nullable()->after('created_at');
            }
            if (!Schema::hasColumn('actualites', 'lieu')) {
                $table->string('lieu')->nullable()->after('date_evenement');
            }
            if (!Schema::hasColumn('actualites', 'rapport_url')) {
                $table->string('rapport_url')->nullable()->after('lieu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            $table->dropColumn(['date_evenement', 'lieu', 'rapport_url']);
        });
    }
};
