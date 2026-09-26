<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ShuttleRsvp extends Model
{
    use HasUuids;

    public const DISTANCES = ['100KM', '60KM', '21KM', '10KM'];

    // Value => label shown on the public form and in admin
    public const SHUTTLE_DATES = [
        '2026-11-13' => 'Friday, November 13, 2026',
        '2026-11-14' => 'Saturday, November 14, 2026',
    ];

    // Value => description shown under each pickup option
    public const PICKUP_POINTS = [
        'NAIA'  => 'Ninoy Aquino International Airport',
        'Pasay' => 'Pasay bus terminal area',
        'Cubao' => 'Cubao bus terminal area',
    ];

    public const MAX_SEATS = 10;

    protected $fillable = [
        'full_name',
        'email',
        'mobile_number',
        'distance',
        'seats',
        'shuttle_date',
        'return_trip',
        'pickup_point',
        'notes',
    ];

    protected $casts = [
        'seats'        => 'integer',
        'shuttle_date' => 'date',
        'return_trip'  => 'boolean',
    ];

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('mobile_number', 'like', "%{$term}%");
        });
    }
}
