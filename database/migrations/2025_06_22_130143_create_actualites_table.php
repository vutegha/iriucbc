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
        Schema::create('actualites', function (Blueprint $table) {
    $table->id();
    $table->string('slug')->unique();
    $table->string('titre');
    $table->string('resume')->nullable();;
    $table->text('texte');
    $table->string('image')->nullable();
    $table->boolean('a_la_une')->default(false);
    $table->boolean('en_vedette')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actualites');
    }
};
