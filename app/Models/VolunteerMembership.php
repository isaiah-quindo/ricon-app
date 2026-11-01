<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VolunteerMembership extends Model
{
    use HasUuids;

    public const FEE = 100;

    // Value => label shown on the public form and in admin
    public const ORIENTATION_DATES = [
        '2026-10-17' => 'Saturday, October 17, 2026',
        '2026-10-22' => 'Thursday, October 22, 2026',
    ];

    // Start time per orientation date
    public const ORIENTATION_TIMES = [
        '2026-10-17' => '2:00 PM',
        '2026-10-22' => '7:00 PM',
    ];

    protected $fillable = [
        'full_name',
        'email',
        'mobile_number',
        'orientation_date',
        'gcash_reference',
    ];

    protected $casts = [
        'orientation_date' => 'date',
    ];

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('mobile_number', 'like', "%{$term}%")
              ->orWhere('gcash_reference', 'like', "%{$term}%");
        });
    }
}
