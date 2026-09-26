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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference')->unique();

            // Buyer
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_number');

            // Item, snapshotted so later catalog changes don't rewrite past orders
            $table->string('product');
            $table->string('product_name');
            $table->string('size')->nullable();
            $table->unsignedTinyInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);

            $table->boolean('notify_consent')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
