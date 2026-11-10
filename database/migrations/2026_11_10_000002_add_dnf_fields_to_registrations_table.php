<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Race-day "did not finish": where, why and when. Null while the runner is still racing. */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('dnf_checkpoint', 20)->nullable();
            $table->string('dnf_reason')->nullable();
            $table->timestampTz('dnf_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['dnf_checkpoint', 'dnf_reason', 'dnf_at']);
        });
    }
};
