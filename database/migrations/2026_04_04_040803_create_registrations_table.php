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
        Schema::create('registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('race_category_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('mobile_number');
            $table->string('email');
            $table->date('birthdate');
            $table->string('address');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number');
            $table->string('shirt_size');
            $table->boolean('waiver_agreed')->default(false);
            $table->boolean('terms_agreed')->default(false);
            $table->integer('bib_number')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
