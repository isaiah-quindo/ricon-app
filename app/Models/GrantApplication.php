<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GrantApplication extends Model
{
    use HasUuids;

    protected $fillable = [
        'full_name',
        'date_of_birth',
        'email',
        'mobile_number',
        'city_province',
        'occupation',
        'social_profile',
        'years_running',
        'longest_distance',
        'race_history',
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'employment_status',
        'q6',
        'emergency_contact_name',
        'emergency_contact_number',
        'consent_media',
        'consent_interview',
        'consent_terms',
        'signature',
        'signature_date',
    ];

    protected $casts = [
        'date_of_birth'     => 'date',
        'signature_date'    => 'date',
        'consent_media'     => 'boolean',
        'consent_interview' => 'boolean',
        'consent_terms'     => 'boolean',
    ];

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
