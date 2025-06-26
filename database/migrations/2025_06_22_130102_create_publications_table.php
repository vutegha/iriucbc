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
        Schema::create('publications', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->string('slug')->unique();
    $table->text('resume')->nullable();
    $table->longText('contenu')->nullable();;
    $table->string('fichier_pdf')->nullable();
    $table->string('citation')->nullable();
    $table->boolean('a_la_une')->default(false);
    $table->boolean('en_vedette')->default(false);
    $table->foreignId('auteur_id')->constrained('auteurs')->onDelete('cascade');
    $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
