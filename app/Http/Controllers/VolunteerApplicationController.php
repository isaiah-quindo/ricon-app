<?php

namespace App\Http\Controllers;

use App\Mail\VolunteerApplicationReceived;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VolunteerApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'                  => 'required|string|max:255',
            'email'                      => 'required|email|max:255',
            'mobile_number'              => 'required|string|max:50',
            'city_province'              => 'required|string|max:255',
            'shirt_size'                 => 'required|string|max:10',
            'preferred_hours'            => 'required|string|max:255',
            'available_dates'            => 'required|array|min:1',
            'available_dates.*'          => 'string|max:255',
            'running_experience'         => 'required|array|min:1',
            'running_experience.*'       => 'string|max:255',
            'years_running'              => 'required|string|max:255',
            'longest_trail_run'          => 'nullable|string|max:255',
            'longest_distance'           => 'nullable|string|max:255',
            'previous_event_experience'  => 'nullable|array',
            'previous_event_experience.*' => 'string|max:255',
            'previous_volunteer_roles'   => 'nullable|array',
            'previous_volunteer_roles.*' => 'string|max:255',
            'previous_roles_other'       => 'nullable|string|max:255',
            'skills_certifications'      => 'nullable|array',
            'skills_certifications.*'    => 'string|max:255',
            'physical_readiness'         => 'nullable|array',
            'physical_readiness.*'       => 'string|max:255',
            'preferred_role'             => 'required|string|max:255',
            'preferred_role_other'       => 'nullable|string|max:255',
            'consent_shift'              => 'accepted',
            'consent_benefits'           => 'accepted',
            'consent_alt_role'           => 'boolean',
        ]);

        $application = VolunteerApplication::create($validated);

        // A mail-provider outage must never block someone's application from saving
        try {
            Mail::to($application->email)->send(new VolunteerApplicationReceived($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['status' => 'created']);
    }
}
