<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('eyebrow')->nullable();
            $table->text('intro')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            // Shown struck through next to the price
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->unsignedInteger('usd_price')->nullable();
            // {"Fit": "One size", ...}
            $table->json('specs')->nullable();
            // ["S", "M", ...], null for one-size items
            $table->json('sizes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_colors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('hex', 7);
            // Either a /images/... public path or an s3 key for admin uploads
            $table->string('image_front')->nullable();
            $table->string('image_back')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // One row per color + size combination; this is where stock lives
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_color_id')->constrained()->cascadeOnDelete();
            $table->string('size')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->timestamps();

            $table->unique(['product_color_id', 'size']);
        });

        $this->seedLaunchCatalog();
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_colors');
        Schema::dropIfExists('products');
    }

    // The two launch items from the TGC100 merch page. Stock starts at 0 so nothing
    // sells until an admin enters real counts on the Inventory page.
    private function seedLaunchCatalog(): void
    {
        $now = now();

        $products = [
            [
                'slug'        => 'trail-cap',
                'name'        => 'TGC100 Trail Cap',
                'eyebrow'     => 'The Design',
                'intro'       => 'A lightweight 5-panel trail cap built for exposed ridgelines and long days in the sun, carrying the Cordillera graphic across the front panel.',
                'description' => '5-panel trail cap with a curved brim, ripstop nylon crown, and "THE NORTH FACE GREAT CORDILLERA 100" graphic across the front panel.',
                'specs'       => ['Fit' => 'One size, adjustable strap', 'Fabric' => 'Ripstop nylon, quick-dry', 'Print' => 'Front panel graphic, embroidered logo mark'],
                'sizes'       => null,
                'colors'      => [
                    ['Sage Green', '#8A9A82', '/images/shop/cap-sage.png', null],
                    ['Khaki', '#C9B68A', '/images/shop/cap-khaki.png', null],
                    ['Black', '#2A2A2A', '/images/shop/cap-black.png', null],
                ],
            ],
            [
                'slug'        => 'running-in-constant-tee',
                'name'        => 'Running in Constant Tee',
                'eyebrow'     => 'Also Dropping',
                'intro'       => 'A clean everyday tee for the off-trail days. Small wordmark on the chest, the full TGC100 lockup across the back.',
                'description' => 'Soft everyday cotton tee with "running in constant." printed small on the chest and the full THE NORTH FACE GREAT CORDILLERA 100 lockup printed across the back.',
                'specs'       => ['Fit' => 'Classic unisex fit', 'Fabric' => '100% cotton, 180gsm', 'Print' => 'Small front chest wordmark, full back graphic'],
                'sizes'       => ['S', 'M', 'L', 'XL'],
                'colors'      => [
                    ['Khaki', '#C9B68A', '/images/shop/tee-khaki-front.png', '/images/shop/tee-khaki-back.png'],
                    ['Black', '#1A1A1A', '/images/shop/tee-black-front.png', '/images/shop/tee-black-back.png'],
                    ['Army Green', '#4A521E', '/images/shop/tee-army-front.png', '/images/shop/tee-army-back.png'],
                ],
            ],
        ];

        foreach ($products as $i => $p) {
            $productId = (string) Str::uuid();

            DB::table('products')->insert([
                'id'               => $productId,
                'slug'             => $p['slug'],
                'name'             => $p['name'],
                'eyebrow'          => $p['eyebrow'],
                'intro'            => $p['intro'],
                'description'      => $p['description'],
                'price'            => 650,
                'compare_at_price' => 750,
                'usd_price'        => 11,
                'specs'            => json_encode($p['specs']),
                'sizes'            => $p['sizes'] ? json_encode($p['sizes']) : null,
                'is_active'        => true,
                'sort_order'       => $i,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);

            foreach ($p['colors'] as $c => [$label, $hex, $front, $back]) {
                $colorId = (string) Str::uuid();

                DB::table('product_colors')->insert([
                    'id'          => $colorId,
                    'product_id'  => $productId,
                    'label'       => $label,
                    'hex'         => $hex,
                    'image_front' => $front,
                    'image_back'  => $back,
                    'sort_order'  => $c,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);

                foreach ($p['sizes'] ?? [null] as $size) {
                    DB::table('product_variants')->insert([
                        'id'               => (string) Str::uuid(),
                        'product_id'       => $productId,
                        'product_color_id' => $colorId,
                        'size'             => $size,
                        'stock'            => 0,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ]);
                }
            }
        }
    }
};
