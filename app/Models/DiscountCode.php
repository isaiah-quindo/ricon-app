<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCode extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
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

    /** A code can cover several categories; it applies to entries in any of them. */
    public function raceCategories()
    {
        return $this->belongsToMany(RaceCategory::class, 'discount_code_race_category');
    }

    public function appliesTo(?string $raceCategoryId): bool
    {
        if (! $raceCategoryId) {
            return false;
        }

        return $this->relationLoaded('raceCategories')
            ? $this->raceCategories->contains('id', $raceCategoryId)
            : $this->raceCategories()->whereKey($raceCategoryId)->exists();
    }

    /** @return array<int, string> */
    public function raceCategoryIds(): array
    {
        return $this->raceCategories()->pluck('race_categories.id')->all();
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
        if (! $this->appliesTo($raceCategoryId)) {
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
