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
        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->string('shirt_size')->nullable()->after('city_province');
            $table->string('preferred_hours')->nullable()->after('shirt_size');
            $table->json('available_dates')->nullable()->after('preferred_hours');
            $table->boolean('consent_alt_role')->default(false)->after('consent_benefits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->dropColumn(['shirt_size', 'preferred_hours', 'available_dates', 'consent_alt_role']);
        });
    }
};
