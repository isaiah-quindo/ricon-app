<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Orders used to hold exactly one item. The cart checkout puts any number of
 * items on one order, so line items move to order_items and the order gains
 * shipping, payment proof and fulfilment fields.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            // Null for the September reservations, which predate variants
            $table->foreignUuid('product_variant_id')->nullable()->constrained()->nullOnDelete();

            // Snapshotted so catalog edits never rewrite past orders
            $table->string('product_name');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);

            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->string('shipping_area')->nullable();
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();

            $table->string('payment_proof_path')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmation_emailed_at')->nullable();

            $table->string('tracking_number')->nullable();
            $table->timestamp('shipped_at')->nullable();

            // Sent to the buyer in the rejection email; admin_notes stays internal
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
        });

        DB::table('orders')->orderBy('created_at')->each(function ($order) {
            DB::table('order_items')->insert([
                'id'           => (string) Str::uuid(),
                'order_id'     => $order->id,
                'product_name' => $order->product_name,
                'size'         => $order->size,
                'quantity'     => $order->quantity,
                'unit_price'   => $order->unit_price,
                'line_total'   => $order->total,
                'created_at'   => $order->created_at,
                'updated_at'   => $order->updated_at,
            ]);

            DB::table('orders')->where('id', $order->id)->update(['subtotal' => $order->total]);
        });

        DB::table('orders')->where('status', 'fulfilled')->update(['status' => 'completed']);

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['product', 'product_name', 'size', 'quantity', 'unit_price']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('product')->nullable();
            $table->string('product_name')->nullable();
            $table->string('size')->nullable();
            $table->unsignedTinyInteger('quantity')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
        });

        // Lossy for multi-item orders: only the first line survives
        DB::table('orders')->orderBy('created_at')->each(function ($order) {
            $item = DB::table('order_items')->where('order_id', $order->id)->orderBy('created_at')->first();
            if (! $item) {
                return;
            }

            DB::table('orders')->where('id', $order->id)->update([
                'product'      => Str::slug($item->product_name),
                'product_name' => $item->product_name,
                'size'         => $item->size,
                'quantity'     => $item->quantity,
                'unit_price'   => $item->unit_price,
            ]);
        });

        DB::table('orders')->where('status', 'completed')->update(['status' => 'fulfilled']);

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_verified_by');
            $table->dropColumn([
                'subtotal', 'shipping_area', 'shipping_fee', 'address_line', 'city', 'province', 'postal_code',
                'payment_proof_path', 'payment_verified_at', 'confirmation_emailed_at',
                'tracking_number', 'shipped_at', 'rejection_reason', 'admin_notes',
            ]);
        });

        Schema::dropIfExists('order_items');
    }
};
