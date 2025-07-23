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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email');
            $table->string('sujet');
            $table->text('message');
            $table->enum('statut', ['nouveau', 'lu', 'traite', 'ferme'])->default('nouveau');
            $table->timestamp('lu_a')->nullable();
            $table->timestamp('traite_a')->nullable();
            $table->text('reponse')->nullable();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['statut', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
