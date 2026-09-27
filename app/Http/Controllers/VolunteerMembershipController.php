<?php

namespace App\Http\Controllers;

use App\Models\VolunteerMembership;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VolunteerMembershipController extends Controller
{
    public function create()
    {
        return view('programs.volunteer-membership');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'mobile_number'    => 'required|string|min:7|max:50',
            'orientation_date' => ['required', Rule::in(array_keys(VolunteerMembership::ORIENTATION_DATES))],
            'gcash_reference'  => 'required|string|min:6|max:50',
            'consent'          => 'accepted',
        ]);

        unset($validated['consent']);

        VolunteerMembership::create($validated);

        return response()->json(['status' => 'created']);
    }
}
