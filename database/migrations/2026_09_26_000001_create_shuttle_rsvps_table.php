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
        Schema::create('shuttle_rsvps', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Runner
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_number');

            // Trip
            $table->string('distance');
            $table->unsignedTinyInteger('seats');
            $table->date('shuttle_date');
            $table->boolean('return_trip')->default(false);
            $table->string('pickup_point');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shuttle_rsvps');
    }
};
