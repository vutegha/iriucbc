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
        // Première méthode : utiliser des requêtes SQL brutes pour une migration plus sûre
        
        // 1. Ajouter la colonne resume si elle n'existe pas, sinon modifier son type
        $resumeExists = Schema::hasColumn('services', 'resume');
        if (!$resumeExists) {
            DB::statement('ALTER TABLE services ADD COLUMN resume TEXT NULL AFTER nom');
        } else {
            DB::statement('ALTER TABLE services MODIFY COLUMN resume TEXT NULL');
        }
        
        // 2. Ajouter la colonne contenu si elle n'existe pas
        $contenuExists = Schema::hasColumn('services', 'contenu');
        if (!$contenuExists) {
            DB::statement('ALTER TABLE services ADD COLUMN contenu TEXT NULL');
        }
        
        // 3. Migrer les données
        echo "🔄 Migration des données en cours...\n";
        
        $services = DB::table('services')->get();
        $migrated = 0;
        
        foreach ($services as $service) {
            $updates = [];
            
            // Migrer description vers resume si resume est vide
            if ((!isset($service->resume) || empty($service->resume)) && !empty($service->description)) {
                $updates['resume'] = $service->description;
            }
            
            // Migrer texte_detaille vers contenu si contenu est vide
            if ((!isset($service->contenu) || empty($service->contenu)) && isset($service->texte_detaille) && !empty($service->texte_detaille)) {
                $updates['contenu'] = $service->texte_detaille;
            }
            
            // Mettre à jour si nécessaire
            if (!empty($updates)) {
                DB::table('services')->where('id', $service->id)->update($updates);
                $migrated++;
            }
        }
        
        echo "✅ Migration terminée pour {$migrated} services sur " . count($services) . " au total\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sauvegarder les données avant de supprimer les colonnes
        $services = DB::table('services')->get();
        
        foreach ($services as $service) {
            $updates = [];
            
            // Restaurer resume vers description si nécessaire
            if (!empty($service->resume) && (empty($service->description) || is_null($service->description))) {
                $updates['description'] = $service->resume;
            }
            
            // Restaurer contenu vers texte_detaille si nécessaire
            if (!empty($service->contenu) && (!isset($service->texte_detaille) || empty($service->texte_detaille))) {
                $updates['texte_detaille'] = $service->contenu;
            }
            
            if (!empty($updates)) {
                DB::table('services')->where('id', $service->id)->update($updates);
            }
        }
        
        // Ne pas supprimer les colonnes pour éviter la perte de données
        echo "⚠️  Rollback effectué - les données ont été restaurées mais les nouvelles colonnes sont conservées pour sécurité\n";
    }
};
