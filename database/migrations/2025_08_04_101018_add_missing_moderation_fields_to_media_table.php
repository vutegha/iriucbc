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
        Schema::table('media', function (Blueprint $table) {
            // Ajouter seulement les colonnes manquantes de modération
            $table->unsignedBigInteger('moderated_by')->nullable()->after('status');
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            $table->unsignedBigInteger('created_by')->nullable()->after('moderated_at');
            
            // Champ additionnel pour les raisons de rejet
            $table->text('rejection_reason')->nullable()->after('created_by');
            
            // Contraintes de clés étrangères
            $table->foreign('moderated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Index pour les recherches
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // Supprimer les contraintes de clés étrangères
            $table->dropForeign(['moderated_by']);
            $table->dropForeign(['created_by']);
            
            // Supprimer l'index
            $table->dropIndex(['created_by']);
            
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'moderated_by',
                'moderated_at',
                'created_by',
                'rejection_reason'
            ]);
        });
    }
};
