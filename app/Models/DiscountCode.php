<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DiscountCode extends Model
{
    use HasUuids;

    protected $fillable = [
        'code',
        'race_category_id',
        'discount_percentage',
        'max_uses',
        'used_count',
        'expires_at',
        'one_per_email',
        'is_active',
        'description',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'expires_at'          => 'datetime',
        'one_per_email'       => 'boolean',
        'is_active'           => 'boolean',
        'max_uses'            => 'integer',
        'used_count'          => 'integer',
    ];

    public function raceCategory()
    {
        return $this->belongsTo(RaceCategory::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Returns null when valid, or a user-facing error message when not.
    public function checkValidFor(string $raceCategoryId, ?string $email = null): ?string
    {
        if (! $this->is_active) {
            return 'Code is inactive.';
        }
        if ($this->race_category_id !== $raceCategoryId) {
            return 'Code is not valid for the selected race category.';
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Code has expired.';
        }
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return 'Code has reached its usage limit.';
        }
        if ($this->one_per_email && $email && Registration::where('email', $email)
            ->where('discount_code_id', $this->id)
            ->exists()
        ) {
            return 'You have already used this code.';
        }
        return null;
    }

    public function computeDiscount(float $basePrice): float
    {
        return round($basePrice * ((float) $this->discount_percentage / 100), 2);
    }
}
