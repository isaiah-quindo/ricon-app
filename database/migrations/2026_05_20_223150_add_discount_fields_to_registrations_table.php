<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignUuid('discount_code_id')
                ->nullable()
                ->after('price_paid')
                ->constrained('discount_codes')
                ->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->nullable()->after('discount_code_id');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['discount_code_id']);
            $table->dropColumn(['discount_code_id', 'discount_amount']);
        });
    }
};
