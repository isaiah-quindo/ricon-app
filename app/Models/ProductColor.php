<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ProductColor extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'label',
        'hex',
        'image_front',
        'image_back',
        'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getFrontUrlAttribute(): ?string
    {
        return static::imageUrl($this->image_front);
    }

    public function getBackUrlAttribute(): ?string
    {
        return static::imageUrl($this->image_back);
    }

    // Launch images ship in public/images/shop; admin uploads go to s3
    public static function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, '/') ? $path : Storage::disk('s3')->url($path);
    }
}
