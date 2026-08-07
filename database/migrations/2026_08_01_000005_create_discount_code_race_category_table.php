<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A discount code can now cover several race categories instead of exactly one.
 *
 * Existing codes are carried across to the pivot before the old column is dropped,
 * so nothing loses its category.
 */
return new class extends Migration
{
    public function up(): void
    {
        // A plain pivot: the pair is the key, so no surrogate id to populate.
        Schema::create('discount_code_race_category', function (Blueprint $table) {
            $table->foreignUuid('discount_code_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('race_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['discount_code_id', 'race_category_id']);
        });

        // Carry every existing code's single category over.
        DB::table('discount_codes')
            ->select('id', 'race_category_id')
            ->whereNotNull('race_category_id')
            ->orderBy('id')
            ->chunk(200, function ($codes) {
                DB::table('discount_code_race_category')->insert(
                    $codes->map(fn ($code) => [
                        'discount_code_id' => $code->id,
                        'race_category_id' => $code->race_category_id,
                    ])->all()
                );
            });

        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dropForeign(['race_category_id']);
            $table->dropColumn('race_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            // Nullable on the way back: a code may now cover several categories, and
            // only the first can be represented by a single column.
            $table->foreignUuid('race_category_id')->nullable()
                ->constrained('race_categories')->cascadeOnDelete();
        });

        foreach (DB::table('discount_code_race_category')->get()->groupBy('discount_code_id') as $codeId => $rows) {
            DB::table('discount_codes')
                ->where('id', $codeId)
                ->update(['race_category_id' => $rows->first()->race_category_id]);
        }

        Schema::dropIfExists('discount_code_race_category');
    }
};
