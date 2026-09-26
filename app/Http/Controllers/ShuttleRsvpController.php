<?php

namespace App\Http\Controllers;

use App\Models\ShuttleRsvp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShuttleRsvpController extends Controller
{
    public function create()
    {
        return view('shuttle');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'mobile_number' => 'required|string|min:7|max:50',
            'distance'      => ['required', Rule::in(ShuttleRsvp::DISTANCES)],
            'seats'         => 'required|integer|min:1|max:' . ShuttleRsvp::MAX_SEATS,
            'shuttle_date'  => ['required', Rule::in(array_keys(ShuttleRsvp::SHUTTLE_DATES))],
            'return_trip'   => 'boolean',
            'pickup_point'  => ['required', Rule::in(array_keys(ShuttleRsvp::PICKUP_POINTS))],
            'notes'         => 'nullable|string|max:2000',
        ]);

        ShuttleRsvp::create($validated);

        return response()->json(['status' => 'created']);
    }
}
