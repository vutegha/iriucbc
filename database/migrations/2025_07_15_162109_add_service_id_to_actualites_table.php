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
            // Ajouter la colonne service_id comme clé étrangère nullable
            $table->unsignedBigInteger('service_id')->nullable()->after('id');
            
            // Ajouter la contrainte de clé étrangère
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            
            // Ajouter un index pour optimiser les requêtes
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign(['service_id']);
            
            // Supprimer l'index
            $table->dropIndex(['service_id']);
            
            // Supprimer la colonne
            $table->dropColumn('service_id');
        });
    }
};
