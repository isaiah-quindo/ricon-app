<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'reference',
        'full_name',
        'email',
        'mobile_number',
        'product',
        'product_name',
        'size',
        'quantity',
        'unit_price',
        'total',
        'notify_consent',
    ];

    protected $casts = [
        'quantity'       => 'integer',
        'unit_price'     => 'decimal:2',
        'total'          => 'decimal:2',
        'notify_consent' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->reference ??= static::generateReference();
        });
    }

    // Short code runners can quote when paying or asking about their order, e.g. TGC-7K3QX9.
    // Skips look-alike characters (0/O, 1/I/L) so it survives being read out over the phone.
    public static function generateReference(): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $reference = 'TGC-' . $code;
        } while (static::where('reference', $reference)->exists());

        return $reference;
    }

    public static function products(): array
    {
        return config('shop.products');
    }

    public static function findProduct(string $slug): ?array
    {
        $product = static::products()[$slug] ?? null;

        return $product ? ['slug' => $slug] + $product : null;
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('mobile_number', 'like', "%{$term}%")
              ->orWhere('reference', 'like', "%{$term}%");
        });
    }
}
