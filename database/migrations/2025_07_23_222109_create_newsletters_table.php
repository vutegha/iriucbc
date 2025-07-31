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
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nom')->nullable();
            $table->string('token')->unique(); // Utilisé dans le modèle au lieu de 'token_desinscription'
            $table->boolean('actif')->default(true);
            $table->timestamp('confirme_a')->nullable(); // Utilisé dans le modèle au lieu de 'confirme_at'
            $table->string('ip_address')->nullable();
            $table->timestamp('derniere_activite')->nullable();
            $table->timestamps();
            
            $table->index('actif');
            $table->index('email');
            $table->index('confirme_a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
