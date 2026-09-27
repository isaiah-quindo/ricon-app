<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerMembership;
use Illuminate\Http\Request;

class VolunteerMembershipController extends Controller
{
    public function index(Request $request)
    {
        $memberships = $this->filtered($request)
            ->when(true, function ($q) use ($request) {
                $sortable = ['full_name', 'orientation_date', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.volunteer_memberships.index', [
            'memberships' => $memberships,
            'total'       => VolunteerMembership::count(),
        ]);
    }

    public function export(Request $request)
    {
        $memberships = $this->filtered($request)->latest()->get();

        $filename = 'volunteer-memberships-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($memberships) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id', 'full_name', 'email', 'mobile_number', 'orientation_date', 'gcash_reference', 'submitted_at',
            ]);

            foreach ($memberships as $membership) {
                fputcsv($handle, [
                    $membership->id,
                    $membership->full_name,
                    $membership->email,
                    $membership->mobile_number,
                    $membership->orientation_date->toDateString(),
                    $membership->gcash_reference,
                    $membership->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function filtered(Request $request)
    {
        return VolunteerMembership::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->orientation_date, fn($q) => $q->whereDate('orientation_date', $request->orientation_date));
    }
}
