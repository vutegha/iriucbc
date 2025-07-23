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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['CDI', 'CDD', 'Stage', 'Freelance', 'Temps partiel']);
            $table->string('location');
            $table->string('department');
            $table->enum('source', ['interne', 'partenaire'])->default('interne');
            $table->string('partner_name')->nullable(); // Nom du partenaire si source = partenaire
            $table->enum('status', ['active', 'expired', 'draft'])->default('active');
            $table->date('application_deadline')->nullable();
            $table->json('requirements')->nullable(); // Liste des exigences
            $table->json('criteria')->nullable(); // Critères dynamiques pour le questionnaire
            $table->text('benefits')->nullable(); // Avantages du poste
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->boolean('salary_negotiable')->default(false);
            $table->integer('positions_available')->default(1);
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_featured')->default(false); // Mise en avant
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'application_deadline']);
            $table->index(['source', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
