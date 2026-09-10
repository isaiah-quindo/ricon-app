@extends('layouts.admin')
@section('title', 'Volunteer Application Detail')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.volunteer-applications.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Volunteer Applications
    </a>
</div>

@php
$badgeList = function (?array $items) {
    if (empty($items)) {
        return '<p class="text-sm text-gray-400">None selected.</p>';
    }
    $badges = collect($items)->map(fn($item) => '<span class="inline-flex items-center px-2 py-1 bg-gray-50 border border-gray-200 text-gray-700 text-xs font-medium rounded-md">' . e($item) . '</span>')->implode(' ');
    return '<div class="flex flex-wrap gap-1.5">' . $badges . '</div>';
};
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Header card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $application->full_name }}</h2>
                    <p class="text-sm text-gray-500">{{ $application->email }}</p>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap">
                    Submitted {{ $application->created_at->format('M j, Y g:i A') }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Mobile</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->mobile_number }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">City / Province</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->city_province }}</p>
                </div>
            </div>
        </div>

        {{-- Running background --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Running Background</h3>

            <div class="mb-4">
                <p class="text-xs text-gray-400 mb-1.5">Running Experience</p>
                {!! $badgeList($application->running_experience) !!}
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Years of Running Experience</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->years_running }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Longest Trail Run</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->longest_trail_run ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Longest Distance (Any Discipline)</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->longest_distance ?: '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Event & volunteer experience --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-800">Event &amp; Volunteer Experience</h3>
            <div>
                <p class="text-xs text-gray-400 mb-1.5">Previous Race / Event Experience</p>
                {!! $badgeList($application->previous_event_experience) !!}
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1.5">Previous Volunteer / Event Roles</p>
                {!! $badgeList($application->previous_volunteer_roles) !!}
                @if($application->previous_roles_other)
                <p class="text-sm text-gray-800 mt-2">Other: {{ $application->previous_roles_other }}</p>
                @endif
            </div>
        </div>

        {{-- Skills & readiness --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-800">Skills &amp; Readiness</h3>
            <div>
                <p class="text-xs text-gray-400 mb-1.5">Skills &amp; Certifications</p>
                {!! $badgeList($application->skills_certifications) !!}
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1.5">Physical &amp; Outdoor Readiness</p>
                {!! $badgeList($application->physical_readiness) !!}
            </div>
        </div>

    </div>

    {{-- Right column --}}
    <div class="space-y-4">

        {{-- Preferred role --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Preferred Volunteer Role</h3>
            <p class="text-sm text-gray-700">
                {{ $application->preferred_role === 'Other' ? ($application->preferred_role_other ?: 'Other') : $application->preferred_role }}
            </p>
        </div>

        {{-- Consent --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Consent</h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500">Can commit to shift length</span>
                    @if($application->consent_shift)
                    <span class="inline-flex items-center px-2 py-0.5 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-md">Agreed</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium rounded-md">No</span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500">Understands benefits tied to hours</span>
                    @if($application->consent_benefits)
                    <span class="inline-flex items-center px-2 py-0.5 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-md">Agreed</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium rounded-md">No</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div class="bg-orange-50 rounded-xl border border-orange-100 p-5">
            <h3 class="text-sm font-semibold text-orange-800 mb-2">Contact Applicant</h3>
            <a href="mailto:{{ $application->email }}" class="text-sm text-orange-700 hover:text-orange-800 break-words">
                {{ $application->email }}
            </a>
            <p class="text-sm text-orange-700 mt-1">{{ $application->mobile_number }}</p>
        </div>

    </div>
</div>

@endsection
