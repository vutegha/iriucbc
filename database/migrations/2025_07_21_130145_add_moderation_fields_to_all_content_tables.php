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
        // Ajouter les champs de modération aux actualités
        Schema::table('actualites', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->text('moderation_comment')->nullable();
            
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_published']);
        });

        // Ajouter les champs de modération aux publications
        Schema::table('publications', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->text('moderation_comment')->nullable();
            
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_published']);
        });

        // Ajouter les champs de modération aux projets
        Schema::table('projets', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->text('moderation_comment')->nullable();
            
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_published']);
        });

        // Ajouter les champs de modération aux services
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->boolean('show_in_menu')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->text('moderation_comment')->nullable();
            
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_published', 'show_in_menu']);
        });

        // Ajouter les champs de modération aux rapports
        Schema::table('rapports', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->text('moderation_comment')->nullable();
            
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les champs de modération de toutes les tables
        $tables = ['actualites', 'publications', 'projets', 'services', 'rapports'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropForeign(['published_by']);
                $blueprint->dropIndex(['is_published']);
                $blueprint->dropColumn(['is_published', 'published_at', 'published_by', 'moderation_comment']);
            });
        }

        // Pour services, supprimer aussi show_in_menu
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('show_in_menu');
        });
    }
};
