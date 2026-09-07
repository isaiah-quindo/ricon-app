<?php

namespace App\Http\Controllers;

use App\Mail\GrantApplicationReceived;
use App\Models\GrantApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GrantApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'                => 'required|string|max:255',
            'date_of_birth'            => 'required|date|before:-16 years',
            'email'                    => 'required|email|max:255',
            'mobile_number'            => 'required|string|max:50',
            'city_province'            => 'required|string|max:255',
            'occupation'               => 'required|string|max:255',
            'social_profile'           => 'required|string|max:255',
            'years_running'            => 'required|string|max:255',
            'longest_distance'         => 'required|string|max:255',
            'race_history'             => 'nullable|string',
            'q1'                       => 'required|string',
            'q2'                       => 'required|string',
            'q3'                       => 'required|string',
            'q4'                       => 'required|string',
            'q5'                       => 'nullable|string',
            'employment_status'        => 'required|string|max:255',
            'q6'                       => 'nullable|string',
            'emergency_contact_name'   => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:50',
            'consent_media'            => 'accepted',
            'consent_interview'        => 'accepted',
            'consent_terms'            => 'accepted',
            'signature'                => 'nullable|string|max:255',
            'signature_date'           => 'nullable|date',
        ]);

        $application = GrantApplication::create($validated);

        // A mail-provider outage must never block someone's application from saving
        try {
            Mail::to($application->email)->send(new GrantApplicationReceived($application));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['status' => 'created']);
    }
}
