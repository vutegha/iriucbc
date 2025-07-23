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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->datetime('date_evenement');
            $table->datetime('date_fin_evenement')->nullable();
            $table->string('lieu')->nullable();
            $table->text('adresse')->nullable();
            $table->string('organisateur')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->decimal('prix', 10, 2)->nullable()->default(0);
            $table->integer('places_disponibles')->nullable();
            $table->text('programme')->nullable(); // Programme de l'événement
            $table->string('rapport_url')->nullable(); // Lien vers le rapport post-événement
            $table->enum('statut', ['brouillon', 'publie', 'annule', 'reporte'])->default('brouillon');
            $table->enum('type', ['conference', 'atelier', 'seminaire', 'formation', 'symposium', 'autre'])->default('autre');
            $table->boolean('inscription_requise')->default(false);
            $table->datetime('date_limite_inscription')->nullable();
            $table->json('meta_data')->nullable(); // Pour stocker des données supplémentaires
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['date_evenement', 'statut']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
