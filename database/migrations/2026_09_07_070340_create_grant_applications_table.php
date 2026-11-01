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
        Schema::create('grant_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Personal
            $table->string('full_name');
            $table->string('date_of_birth');
            $table->string('email');
            $table->string('mobile_number');
            $table->string('city_province');
            $table->string('occupation');
            $table->string('social_profile');

            // Running background
            $table->string('years_running');
            $table->string('longest_distance');
            $table->text('race_history')->nullable();

            // About you / about your race
            $table->text('q1');
            $table->text('q2');
            $table->text('q3');
            $table->text('q4');
            $table->text('q5')->nullable();
            $table->string('employment_status');
            $table->text('q6')->nullable();

            // Emergency contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number');

            // Consent
            $table->boolean('consent_media')->default(false);
            $table->boolean('consent_interview')->default(false);
            $table->boolean('consent_terms')->default(false);

            // Signature
            $table->string('signature')->nullable();
            $table->string('signature_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_applications');
    }
};
