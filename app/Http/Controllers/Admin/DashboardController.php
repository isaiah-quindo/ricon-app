<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RaceCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'     => Registration::count(),
            'pending'   => Registration::where('status', 'payment_submitted')->count(),
            'approved'  => Registration::where('status', 'approved')->count(),
            'rejected'  => Registration::where('status', 'rejected')->count(),
        ];

        $byCategory = RaceCategory::withCount([
            'registrations',
            'registrations as approved_count' => fn($q) => $q->where('status', 'approved'),
        ])->get();

        return view('admin.dashboard', compact('stats', 'byCategory'));
    }
}
