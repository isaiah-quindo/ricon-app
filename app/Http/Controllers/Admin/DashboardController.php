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
            'revenue'   => Registration::where('status', 'approved')->sum('price_paid'),
        ];

        $byCategory = RaceCategory::withCount([
            'registrations',
            'registrations as approved_count' => fn($q) => $q->where('status', 'approved'),
        ])->withSum(
            ['registrations as approved_revenue' => fn($q) => $q->where('status', 'approved')],
            'price_paid'
        )->get();

        return view('admin.dashboard', compact('stats', 'byCategory'));
    }
}
