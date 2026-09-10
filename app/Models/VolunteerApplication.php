<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    use HasUuids;

    protected $fillable = [
        'full_name',
        'email',
        'mobile_number',
        'city_province',
        'running_experience',
        'years_running',
        'longest_trail_run',
        'longest_distance',
        'previous_event_experience',
        'previous_volunteer_roles',
        'previous_roles_other',
        'skills_certifications',
        'physical_readiness',
        'preferred_role',
        'preferred_role_other',
        'consent_shift',
        'consent_benefits',
    ];

    protected $casts = [
        'running_experience'         => 'array',
        'previous_event_experience'  => 'array',
        'previous_volunteer_roles'   => 'array',
        'skills_certifications'      => 'array',
        'physical_readiness'         => 'array',
        'consent_shift'              => 'boolean',
        'consent_benefits'           => 'boolean',
    ];

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
