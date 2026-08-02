<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_code')->unique();
            $table->string('leader_email');
            $table->unsignedInteger('participant_count');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('group_discount_percentage', 5, 2)->default(0);
            // none | group | code — which discount actually won.
            $table->string('discount_source')->default('none');
            $table->foreignUuid('discount_code_id')
                ->nullable()
                ->constrained('discount_codes')
                ->nullOnDelete();
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('total_due', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_groups');
    }
};
