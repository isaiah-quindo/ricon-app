<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Nullable so registrations made before group support stay valid.
            $table->foreignUuid('registration_group_id')
                ->nullable()
                ->after('discount_amount')
                ->constrained('registration_groups')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['registration_group_id']);
            $table->dropColumn('registration_group_id');
        });
    }
};
