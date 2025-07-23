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
        // Ajouter les champs de modération aux projets si ils n'existent pas
        if (!Schema::hasColumn('projets', 'is_published')) {
            Schema::table('projets', function (Blueprint $table) {
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('published_by')->nullable();
                $table->text('moderation_comment')->nullable();
                
                $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['is_published']);
            });
        }

        // Ajouter les champs de modération aux événements si ils n'existent pas
        if (!Schema::hasColumn('evenements', 'is_published')) {
            Schema::table('evenements', function (Blueprint $table) {
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('published_by')->nullable();
                $table->text('moderation_comment')->nullable();
                
                $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['is_published']);
            });
        }

        // Ajouter les champs de modération aux rapports si ils n'existent pas
        if (!Schema::hasColumn('rapports', 'is_published')) {
            Schema::table('rapports', function (Blueprint $table) {
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('published_by')->nullable();
                $table->text('moderation_comment')->nullable();
                
                $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['is_published']);
            });
        }

        // Ajouter les champs de modération aux publications si ils n'existent pas
        if (!Schema::hasColumn('publications', 'is_published')) {
            Schema::table('publications', function (Blueprint $table) {
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('published_by')->nullable();
                $table->text('moderation_comment')->nullable();
                
                $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
                $table->index(['is_published']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['projets', 'evenements', 'rapports', 'publications'];
        
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'is_published')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['published_by']);
                    $blueprint->dropIndex(['is_published']);
                    $blueprint->dropColumn(['is_published', 'published_at', 'published_by', 'moderation_comment']);
                });
            }
        }
    }
};
