<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    use HasUuids;

    // payment_submitted → confirmed → shipped (or picked up) → completed.
    // `pending` is only for the September reservations that were placed without payment.
    public const STATUSES = [
        'pending'           => 'Awaiting Payment',
        'payment_submitted' => 'Payment Submitted',
        'confirmed'         => 'Confirmed',
        'shipped'           => 'Shipped',
        'completed'         => 'Completed',
        'rejected'          => 'Payment Rejected',
        'cancelled'         => 'Cancelled',
    ];

    // Orders in these statuses give their items back to inventory
    public const RELEASES_STOCK = ['rejected', 'cancelled'];

    // Status is deliberately not fillable: only admins move it, through markStatus().
    protected $attributes = [
        'status' => 'payment_submitted',
    ];

    protected $fillable = [
        'reference',
        'full_name',
        'email',
        'mobile_number',
        'subtotal',
        'shipping_area',
        'shipping_fee',
        'total',
        'address_line',
        'city',
        'province',
        'postal_code',
        'payment_proof_path',
        'notify_consent',
    ];

    protected $casts = [
        'subtotal'                => 'decimal:2',
        'shipping_fee'            => 'decimal:2',
        'total'                   => 'decimal:2',
        'notify_consent'          => 'boolean',
        'status_changed_at'       => 'datetime',
        'payment_verified_at'     => 'datetime',
        'confirmation_emailed_at' => 'datetime',
        'shipped_at'              => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->orderBy('created_at');
    }

    public function statusChangedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_changed_by');
    }

    public function paymentVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    /**
     * Moves the order to a new status, returning items to inventory when it's
     * cancelled or rejected and taking them back out if it's reopened.
     *
     * @throws ValidationException when reopening and there's no longer enough stock
     */
    public function markStatus(string $status, User $admin, array $extra = []): void
    {
        DB::transaction(function () use ($status, $admin, $extra) {
            $heldStock = ! in_array($this->status, self::RELEASES_STOCK);
            $holdsStock = ! in_array($status, self::RELEASES_STOCK);

            if ($heldStock && ! $holdsStock) {
                $this->releaseStock();
            } elseif (! $heldStock && $holdsStock) {
                $this->reserveStock();
            }

            $this->forceFill([
                'status'            => $status,
                'status_changed_at' => now(),
                'status_changed_by' => $admin->id,
                ...$extra,
            ])->save();
        });
    }

    /**
     * Takes this order's items out of inventory. Call inside a transaction.
     *
     * @throws ValidationException listing every line that's short
     */
    public function reserveStock(): void
    {
        $short = [];

        foreach ($this->items()->whereNotNull('product_variant_id')->get() as $item) {
            $variant = ProductVariant::whereKey($item->product_variant_id)->lockForUpdate()->first();

            if (! $variant || $variant->stock < $item->quantity) {
                $left = $variant?->stock ?? 0;
                $short[] = "{$item->product_name} ({$item->options_label}): only {$left} left";
                continue;
            }

            $variant->decrement('stock', $item->quantity);
        }

        if ($short) {
            throw ValidationException::withMessages(['items' => $short]);
        }
    }

    public function releaseStock(): void
    {
        foreach ($this->items()->whereNotNull('product_variant_id')->get() as $item) {
            ProductVariant::whereKey($item->product_variant_id)->increment('stock', $item->quantity);
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function isPickup(): bool
    {
        return $this->shipping_area === 'pickup';
    }

    public function getShippingAreaLabelAttribute(): ?string
    {
        return config("shop.shipping_areas.{$this->shipping_area}.label");
    }

    public function getShippingAddressAttribute(): ?string
    {
        $parts = array_filter([$this->address_line, $this->city, $this->province, $this->postal_code]);

        return $parts ? implode(', ', $parts) : null;
    }

    public function getTrackingUrlAttribute(): ?string
    {
        return $this->tracking_number
            ? config('shop.lbc_tracking_url') . urlencode($this->tracking_number)
            : null;
    }

    // e.g. "2 × TGC100 Trail Cap, 1 × Running in Constant Tee"
    public function getItemsSummaryAttribute(): string
    {
        return $this->items->map(fn(OrderItem $i) => "{$i->quantity} × {$i->product_name}")->implode(', ');
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->reference ??= static::generateReference();
        });
    }

    // Short code buyers can quote when asking about their order, e.g. TGC-7K3QX9.
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

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('mobile_number', 'like', "%{$term}%")
              ->orWhere('reference', 'like', "%{$term}%")
              ->orWhere('tracking_number', 'like', "%{$term}%");
        });
    }
}
