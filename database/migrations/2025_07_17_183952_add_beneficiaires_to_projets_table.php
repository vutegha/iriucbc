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
        Schema::table('projets', function (Blueprint $table) {
            $table->integer('beneficiaires_hommes')->nullable()->comment('Nombre de bénéficiaires hommes');
            $table->integer('beneficiaires_femmes')->nullable()->comment('Nombre de bénéficiaires femmes');
            $table->integer('beneficiaires_total')->nullable()->comment('Nombre total de bénéficiaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropColumn(['beneficiaires_hommes', 'beneficiaires_femmes', 'beneficiaires_total']);
        });
    }
};
