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
        Schema::table('media', function (Blueprint $table) {
            // Champs de modération
            $table->enum('status', ['pending', 'approved', 'rejected', 'published'])->default('pending')->after('type');
            $table->boolean('is_public')->default(false)->after('status');
            $table->unsignedBigInteger('moderated_by')->nullable()->after('is_public');
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            $table->unsignedBigInteger('created_by')->nullable()->after('moderated_at');
            
            // Champs additionnels
            $table->text('rejection_reason')->nullable()->after('created_by');
            $table->json('tags')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('file_size')->nullable()->after('tags');
            $table->string('mime_type')->nullable()->after('file_size');
            $table->string('alt_text')->nullable()->after('mime_type');
            
            // Index et contraintes de clés étrangères
            $table->foreign('moderated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Index pour les recherches
            $table->index(['status', 'is_public']);
            $table->index(['created_by']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // Supprimer les contraintes de clés étrangères
            $table->dropForeign(['moderated_by']);
            $table->dropForeign(['created_by']);
            
            // Supprimer les index
            $table->dropIndex(['status', 'is_public']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['type', 'status']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'status',
                'is_public',
                'moderated_by',
                'moderated_at',
                'created_by',
                'rejection_reason',
                'tags',
                'file_size',
                'mime_type',
                'alt_text'
            ]);
        });
    }
};
