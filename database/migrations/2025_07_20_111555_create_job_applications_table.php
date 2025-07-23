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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_offer_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F', 'Autre'])->nullable();
            $table->string('nationality')->nullable();
            $table->text('education')->nullable(); // Formation/Diplômes
            $table->text('experience')->nullable(); // Expérience professionnelle
            $table->text('skills')->nullable(); // Compétences
            $table->text('motivation_letter'); // Lettre de motivation
            $table->json('criteria_responses')->nullable(); // Réponses aux critères dynamiques
            $table->string('cv_path')->nullable(); // Chemin vers le CV
            $table->string('portfolio_path')->nullable(); // Chemin vers le portfolio
            $table->json('additional_documents')->nullable(); // Documents supplémentaires
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'rejected', 'accepted'])->default('pending');
            $table->text('admin_notes')->nullable(); // Notes de l'administrateur
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->decimal('score', 5, 2)->nullable(); // Score d'évaluation
            $table->timestamps();
            
            $table->index(['job_offer_id', 'status']);
            $table->index(['email', 'job_offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
