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
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Personal
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_number');
            $table->string('city_province');

            // Running background
            $table->json('running_experience');
            $table->string('years_running');
            $table->string('longest_trail_run')->nullable();
            $table->string('longest_distance')->nullable();

            // Event & volunteer experience
            $table->json('previous_event_experience')->nullable();
            $table->json('previous_volunteer_roles')->nullable();
            $table->string('previous_roles_other')->nullable();

            // Skills & readiness
            $table->json('skills_certifications')->nullable();
            $table->json('physical_readiness')->nullable();

            // Role preference & consent
            $table->string('preferred_role');
            $table->string('preferred_role_other')->nullable();
            $table->boolean('consent_shift')->default(false);
            $table->boolean('consent_benefits')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
