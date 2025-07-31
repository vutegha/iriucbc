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
        Schema::table('evenements', function (Blueprint $table) {
            // Supprimer les colonnes obsolètes une par une avec vérification
            $columns = Schema::getColumnListing('evenements');
            
            if (in_array('programme', $columns)) {
                $table->dropColumn('programme');
            }
            if (in_array('prix', $columns)) {
                $table->dropColumn('prix');
            }
            if (in_array('date_fin_evenement', $columns)) {
                $table->dropColumn('date_fin_evenement');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->text('programme')->nullable();
            $table->decimal('prix', 10, 2)->nullable();
            $table->datetime('date_fin_evenement')->nullable();
        });
    }
};
