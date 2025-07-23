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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type', ['universite', 'centre_recherche', 'organisation_internationale', 'ong', 'entreprise', 'autre']);
            $table->text('description')->nullable();
            $table->string('logo')->nullable(); // Chemin vers le logo
            $table->string('site_web')->nullable();
            $table->string('email_contact')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('pays')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'en_negociation'])->default('actif');
            $table->date('date_debut_partenariat')->nullable();
            $table->date('date_fin_partenariat')->nullable();
            $table->text('message_specifique')->nullable(); // Message spécifique du partenaire
            $table->json('domaines_collaboration')->nullable(); // Domaines de collaboration
            $table->integer('ordre_affichage')->default(0); // Pour l'ordre d'affichage
            $table->boolean('afficher_publiquement')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'statut']);
            $table->index('ordre_affichage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
