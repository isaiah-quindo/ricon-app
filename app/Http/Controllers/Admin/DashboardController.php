<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        $range = in_array((int) $request->query('range'), [7, 30], true)
            ? (int) $request->query('range')
            : 7;

        $start = Carbon::today()->subDays($range - 1);
        $end   = Carbon::today();

        $rows = DB::table('registrations')
            ->join('payment_proofs', 'payment_proofs.registration_id', '=', 'registrations.id')
            ->where('registrations.status', 'approved')
            ->whereNotNull('payment_proofs.verified_at')
            ->whereBetween('payment_proofs.verified_at', [$start, $end->copy()->endOfDay()])
            ->selectRaw('DATE(payment_proofs.verified_at) as date, SUM(registrations.price_paid) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $revenueSeries = collect();
        foreach (CarbonPeriod::create($start, $end) as $day) {
            $key = $day->toDateString();
            $revenueSeries->push([
                'date'  => $day->format('M j'),
                'total' => (float) ($rows[$key] ?? 0),
            ]);
        }

        return view('admin.dashboard', compact('stats', 'byCategory', 'revenueSeries', 'range'));
    }
}
