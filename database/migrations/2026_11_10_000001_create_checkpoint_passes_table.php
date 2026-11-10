<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per runner per timed checkpoint: when they arrived and when they left.
     * `checkpoint` is the code from config/live.php ("as6", "finish").
     */
    public function up(): void
    {
        Schema::create('checkpoint_passes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('registration_id')->constrained()->cascadeOnDelete();
            $table->string('checkpoint', 20);
            $table->timestampTz('in_at')->nullable();
            $table->timestampTz('out_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['registration_id', 'checkpoint']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkpoint_passes');
    }
};
