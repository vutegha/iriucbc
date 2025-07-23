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
        Schema::table('services', function (Blueprint $table) {
            // Supprimer le champ icone s'il existe
            if (Schema::hasColumn('services', 'icone')) {
                $table->dropColumn('icone');
            }
            
            // Supprimer le champ contenu s'il existe
            if (Schema::hasColumn('services', 'contenu')) {
                $table->dropColumn('contenu');
            }
            
            // Le champ resume reste en TEXT - pas de changement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Restaurer les champs supprimés
            $table->string('icone')->nullable();
            $table->text('contenu')->nullable();
            
            // Le champ resume reste en TEXT - pas de changement
        });
    }
};
