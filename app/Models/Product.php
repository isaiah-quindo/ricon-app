<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'slug',
        'name',
        'eyebrow',
        'intro',
        'description',
        'price',
        'compare_at_price',
        'usd_price',
        'specs',
        'sizes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'usd_price'        => 'integer',
        'specs'            => 'array',
        'sizes'            => 'array',
        'is_active'        => 'boolean',
        'sort_order'       => 'integer',
    ];

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function hasSizes(): bool
    {
        return ! empty($this->sizes);
    }

    /**
     * Makes sure there's exactly one variant per color + size, keeping the stock
     * of combinations that survive. Removed combinations are deleted; their past
     * order lines keep their snapshot and just lose the link.
     */
    public function syncVariants(): void
    {
        $sizes = $this->hasSizes() ? $this->sizes : [null];
        $keep = [];

        foreach ($this->colors()->get() as $color) {
            foreach ($sizes as $size) {
                $keep[] = $this->variants()->firstOrCreate(
                    ['product_color_id' => $color->id, 'size' => $size],
                    ['stock' => 0],
                )->id;
            }
        }

        $this->variants()->whereNotIn('id', $keep)->delete();
    }

    /**
     * Everything the cart needs to render and price a line, keyed by variant id.
     * Prices and stock always come from here, never from the browser.
     */
    public static function catalog(): array
    {
        return static::active()->ordered()
            ->with(['colors', 'variants'])
            ->get()
            ->flatMap(function (Product $product) {
                $colors = $product->colors->keyBy('id');

                return $product->variants->map(fn(ProductVariant $v) => [
                    'id'         => $v->id,
                    'product'    => $product->slug,
                    'name'       => $product->name,
                    'color'      => $colors[$v->product_color_id]->label ?? null,
                    'colorId'    => $v->product_color_id,
                    'size'       => $v->size,
                    'price'      => (float) $product->price,
                    'stock'      => $v->stock,
                    'image'      => $colors[$v->product_color_id]?->front_url,
                ]);
            })
            ->keyBy('id')
            ->all();
    }
}
