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
        Schema::create('visitor_locations', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('visit_count')->default(1);
            $table->timestamp('first_visit')->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->timestamps();
            
            $table->unique('ip_address');
            $table->index(['country_code', 'last_visit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_locations');
    }
};
