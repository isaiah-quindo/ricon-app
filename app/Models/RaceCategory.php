<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RaceCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'distance_km',
        'elevation_m',
        'description',
        'max_slots',
        'bib_start_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Registrations that hold a slot. Anything not rejected counts, so a runner awaiting
     * payment review keeps their place and a rejection frees it again.
     */
    public function slotHoldingRegistrations()
    {
        return $this->registrations()->where('status', '!=', 'rejected');
    }

    /** Adds `taken_slots` to the query so the form can render full categories without N+1s. */
    public function scopeWithTakenSlots($query)
    {
        return $query->withCount(['slotHoldingRegistrations as taken_slots']);
    }

    public function remainingSlots(): int
    {
        $taken = $this->taken_slots ?? $this->slotHoldingRegistrations()->count();

        return max(0, $this->max_slots - $taken);
    }

    public function isFull(): bool
    {
        return $this->remainingSlots() === 0;
    }
}
