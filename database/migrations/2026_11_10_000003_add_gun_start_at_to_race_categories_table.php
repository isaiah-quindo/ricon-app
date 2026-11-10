<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * When the gun went off, set by an admin. The source of truth for the race start: live
     * tracking counts race time, ETAs and splits from it. Null means not started yet.
     */
    public function up(): void
    {
        Schema::table('race_categories', function (Blueprint $table) {
            $table->timestampTz('gun_start_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('race_categories', function (Blueprint $table) {
            $table->dropColumn('gun_start_at');
        });
    }
};
