<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The organizer gets one aggregated summary once the group is fully resolved.
 * This timestamp keeps it to one send, however many approval actions ran.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_groups', function (Blueprint $table) {
            $table->timestamp('organizer_notified_at')->nullable()->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('registration_groups', function (Blueprint $table) {
            $table->dropColumn('organizer_notified_at');
        });
    }
};
