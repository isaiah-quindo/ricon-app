<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;

class VolunteerApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = VolunteerApplication::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when(true, function ($q) use ($request) {
                $sortable = ['full_name', 'email', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.volunteer_applications.index', [
            'applications' => $applications,
            'total'        => VolunteerApplication::count(),
        ]);
    }

    public function show(VolunteerApplication $volunteerApplication)
    {
        return view('admin.volunteer_applications.show', [
            'application' => $volunteerApplication,
        ]);
    }

    public function export(Request $request)
    {
        $applications = VolunteerApplication::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->latest()
            ->get();

        $filename = 'volunteer-applications-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id', 'full_name', 'email', 'mobile_number', 'city_province',
                'shirt_size', 'preferred_hours', 'available_dates',
                'running_experience', 'years_running', 'longest_trail_run', 'longest_distance',
                'previous_event_experience', 'previous_volunteer_roles', 'previous_roles_other',
                'skills_certifications', 'physical_readiness',
                'preferred_role', 'preferred_role_other',
                'consent_shift', 'consent_benefits', 'consent_alt_role', 'submitted_at',
            ]);

            foreach ($applications as $application) {
                fputcsv($handle, [
                    $application->id,
                    $application->full_name,
                    $application->email,
                    $application->mobile_number,
                    $application->city_province,
                    $application->shirt_size,
                    $application->preferred_hours,
                    implode('; ', $application->available_dates ?? []),
                    implode('; ', $application->running_experience ?? []),
                    $application->years_running,
                    $application->longest_trail_run,
                    $application->longest_distance,
                    implode('; ', $application->previous_event_experience ?? []),
                    implode('; ', $application->previous_volunteer_roles ?? []),
                    $application->previous_roles_other,
                    implode('; ', $application->skills_certifications ?? []),
                    implode('; ', $application->physical_readiness ?? []),
                    $application->preferred_role,
                    $application->preferred_role_other,
                    $application->consent_shift ? 'yes' : 'no',
                    $application->consent_benefits ? 'yes' : 'no',
                    $application->consent_alt_role ? 'yes' : 'no',
                    $application->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
