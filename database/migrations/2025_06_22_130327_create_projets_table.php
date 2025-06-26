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
        Schema::create('projets', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->string('slug')->unique();
    $table->text('description');
    $table->string('image')->nullable();
    $table->date('date_debut')->nullable();
    $table->date('date_fin')->nullable();
    $table->string('etat')->default('en cours');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
