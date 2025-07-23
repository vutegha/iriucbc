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
        // Étape 1: Ajouter les nouvelles colonnes sans supprimer les anciennes
        Schema::table('services', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes
            if (!Schema::hasColumn('services', 'resume')) {
                $table->text('resume')->nullable()->after('nom');
            }
            
            if (!Schema::hasColumn('services', 'contenu')) {
                $table->text('contenu')->nullable();
            }
        });

        // Étape 2: Migrer les données existantes vers les nouvelles colonnes
        $services = DB::table('services')->get();
        
        foreach ($services as $service) {
            $updates = [];
            
            // Migrer description vers resume si resume est vide
            if (empty($service->resume) && !empty($service->description)) {
                $updates['resume'] = $service->description;
            }
            
            // Migrer texte_detaille vers contenu si contenu est vide
            if (empty($service->contenu) && isset($service->texte_detaille) && !empty($service->texte_detaille)) {
                $updates['contenu'] = $service->texte_detaille;
            }
            
            // Mettre à jour si nécessaire
            if (!empty($updates)) {
                DB::table('services')->where('id', $service->id)->update($updates);
            }
        }
        
        echo "✅ Migration des données terminée pour " . count($services) . " services\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Supprimer seulement les nouvelles colonnes ajoutées
            if (Schema::hasColumn('services', 'resume')) {
                $table->dropColumn('resume');
            }
            
            if (Schema::hasColumn('services', 'contenu')) {
                $table->dropColumn('contenu');
            }
        });
    }
};
