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
        Schema::create('chercheur_affilies', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('titre_academique'); // Dr, Prof, etc.
            $table->string('institution_origine');
            $table->string('departement')->nullable();
            $table->string('domaine_recherche');
            $table->text('specialites')->nullable(); // Spécialités de recherche
            $table->string('photo')->nullable(); // Photo du chercheur
            $table->text('biographie')->nullable();
            $table->string('orcid')->nullable(); // ORCID ID
            $table->string('google_scholar')->nullable(); // Lien Google Scholar
            $table->string('researchgate')->nullable(); // Lien ResearchGate
            $table->string('linkedin')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->date('date_affiliation');
            $table->date('date_fin_affiliation')->nullable();
            $table->json('publications_collaboratives')->nullable(); // Publications avec IRI-UCBC
            $table->json('projets_collaboration')->nullable(); // Projets en collaboration
            $table->text('contributions')->nullable(); // Contributions spécifiques
            $table->boolean('afficher_publiquement')->default(true);
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();
            
            $table->index(['statut', 'afficher_publiquement']);
            $table->index('domaine_recherche');
            $table->index('ordre_affichage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chercheur_affilies');
    }
};
