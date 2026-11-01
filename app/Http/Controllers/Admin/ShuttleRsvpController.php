<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShuttleRsvp;
use Illuminate\Http\Request;

class ShuttleRsvpController extends Controller
{
    public function index(Request $request)
    {
        $rsvps = $this->filtered($request)
            ->when(true, function ($q) use ($request) {
                $sortable = ['full_name', 'shuttle_date', 'seats', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.shuttle_rsvps.index', [
            'rsvps' => $rsvps,
            'total' => ShuttleRsvp::count(),
        ]);
    }

    public function export(Request $request)
    {
        $rsvps = $this->filtered($request)->latest()->get();

        $filename = 'shuttle-rsvps-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rsvps) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id', 'full_name', 'email', 'mobile_number', 'distance', 'seats',
                'shuttle_date', 'pickup_point', 'return_trip', 'notes', 'submitted_at',
            ]);

            foreach ($rsvps as $rsvp) {
                fputcsv($handle, [
                    $rsvp->id,
                    $rsvp->full_name,
                    $rsvp->email,
                    $rsvp->mobile_number,
                    $rsvp->distance,
                    $rsvp->seats,
                    $rsvp->shuttle_date->toDateString(),
                    $rsvp->pickup_point,
                    $rsvp->return_trip ? 'yes' : 'no',
                    $rsvp->notes,
                    $rsvp->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function filtered(Request $request)
    {
        return ShuttleRsvp::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->shuttle_date, fn($q) => $q->whereDate('shuttle_date', $request->shuttle_date))
            ->when($request->pickup_point, fn($q) => $q->where('pickup_point', $request->pickup_point))
            ->when($request->distance, fn($q) => $q->where('distance', $request->distance));
    }
}
