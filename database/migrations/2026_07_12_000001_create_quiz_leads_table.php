<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('source', 30)->index(); // where the lead was captured, e.g. '21k_quiz'
            $table->unsignedTinyInteger('score');
            $table->string('result', 1); // 'a' ready | 'b' almost there | 'c' go 10K first
            $table->timestamp('mailchimp_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_leads');
    }
};
