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
        Schema::create('volunteer_memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Volunteer
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_number');

            $table->date('orientation_date');

            // Membership fee, paid over GCash before the form is sent
            $table->string('gcash_reference');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_memberships');
    }
};
