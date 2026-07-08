<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_signups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('plan', 20); // 'tgc100k' | 'tgc60k'
            $table->boolean('registered_tgc')->default(false);
            $table->string('token', 64)->unique();
            $table->date('started_on');
            $table->timestamp('link_last_sent_at')->nullable();
            $table->timestamp('mailchimp_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_signups');
    }
};
