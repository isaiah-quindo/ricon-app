<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RaceCategory extends Model
{
    use HasUuids;

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
}
