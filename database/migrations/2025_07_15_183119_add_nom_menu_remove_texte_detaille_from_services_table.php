<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Ajouter la colonne nom_menu pour le menu dynamique
            $table->string('nom_menu')->nullable()->after('nom');
            
            // Supprimer la colonne texte_detaille (remplacée par contenu)
            if (Schema::hasColumn('services', 'texte_detaille')) {
                $table->dropColumn('texte_detaille');
            }
        });
        
        // Peupler nom_menu avec le nom existant pour les services existants
        DB::table('services')->whereNull('nom_menu')->update([
            'nom_menu' => DB::raw('nom')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Restaurer la colonne texte_detaille
            $table->text('texte_detaille')->nullable()->after('description');
            
            // Supprimer nom_menu
            if (Schema::hasColumn('services', 'nom_menu')) {
                $table->dropColumn('nom_menu');
            }
        });
    }
};
