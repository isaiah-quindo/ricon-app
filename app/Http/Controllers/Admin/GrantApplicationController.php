<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrantApplication;
use Illuminate\Http\Request;

class GrantApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = GrantApplication::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when(true, function ($q) use ($request) {
                $sortable = ['full_name', 'email', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.grant_applications.index', [
            'applications' => $applications,
            'total'        => GrantApplication::count(),
        ]);
    }

    public function show(GrantApplication $grantApplication)
    {
        return view('admin.grant_applications.show', [
            'application' => $grantApplication,
        ]);
    }

    public function export(Request $request)
    {
        $applications = GrantApplication::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->latest()
            ->get();

        $filename = 'grant-applications-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id', 'full_name', 'date_of_birth', 'email', 'mobile_number',
                'city_province', 'occupation', 'social_profile',
                'years_running', 'longest_distance', 'race_history',
                'q1', 'q2', 'q3', 'q4', 'q5', 'employment_status', 'q6',
                'emergency_contact_name', 'emergency_contact_number',
                'consent_media', 'consent_interview', 'consent_terms',
                'signature', 'signature_date', 'submitted_at',
            ]);

            foreach ($applications as $application) {
                fputcsv($handle, [
                    $application->id,
                    $application->full_name,
                    $application->date_of_birth->format('Y-m-d'),
                    $application->email,
                    $application->mobile_number,
                    $application->city_province,
                    $application->occupation,
                    $application->social_profile,
                    $application->years_running,
                    $application->longest_distance,
                    $application->race_history,
                    $application->q1,
                    $application->q2,
                    $application->q3,
                    $application->q4,
                    $application->q5,
                    $application->employment_status,
                    $application->q6,
                    $application->emergency_contact_name,
                    $application->emergency_contact_number,
                    $application->consent_media ? 'yes' : 'no',
                    $application->consent_interview ? 'yes' : 'no',
                    $application->consent_terms ? 'yes' : 'no',
                    $application->signature,
                    $application->signature_date?->format('Y-m-d'),
                    $application->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
