@extends('layouts.admin')
@section('title', 'Grant Application Detail')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.grant-applications.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Grant Applications
    </a>
</div>

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
                    <p class="text-xs text-gray-400 mb-0.5">Date of Birth</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->date_of_birth->format('M j, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Mobile</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->mobile_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">City / Province</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->city_province }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Occupation</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->occupation }}</p>
                </div>
                <div class="col-span-2 sm:col-span-3">
                    <p class="text-xs text-gray-400 mb-0.5">Strava / Instagram / Facebook</p>
                    <p class="text-sm font-medium text-gray-800 break-words">{{ $application->social_profile }}</p>
                </div>
            </div>
        </div>

        {{-- Running background --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Running Background</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Years Running Trail / Ultra</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->years_running }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Longest Distance Completed</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->longest_distance }}</p>
                </div>
            </div>
            @if($application->race_history)
            <div>
                <p class="text-xs text-gray-400 mb-1">Notable Race History</p>
                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $application->race_history }}</p>
            </div>
            @endif
        </div>

        {{-- Questions --}}
        @php
        $questions = [
            'q1' => "Describe your current training approach and how you've stayed committed to the sport, including through setbacks.",
            'q2' => 'How have you contributed to your running or trail community?',
            'q3' => 'Why does running matter to you?',
            'q4' => 'Why The Great Cordillera 100, specifically, and why this year?',
            'q5' => 'How would financial support affect your ability to take part?',
            'q6' => 'What does taking on 100 kilometers mean to you, and how are you preparing for it?',
        ];
        @endphp
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="text-sm font-semibold text-gray-800">Application Answers</h3>
            @foreach($questions as $key => $label)
            @if($application->{$key})
            <div class="pt-4 border-t border-gray-100 first:pt-0 first:border-0">
                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-1">{{ strtoupper($key) }}. {{ $label }}</p>
                <p class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">{{ $application->{$key} }}</p>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Emergency Contact --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Emergency Contact
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Name</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->emergency_contact_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Phone</p>
                    <p class="text-sm font-medium text-gray-800">{{ $application->emergency_contact_number }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Right column --}}
    <div class="space-y-4">

        {{-- Employment status --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Employment / Income Status</h3>
            <p class="text-sm text-gray-700">{{ $application->employment_status }}</p>
        </div>

        {{-- Consent --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Consent</h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500">Media &amp; documentation</span>
                    @if($application->consent_media)
                    <span class="inline-flex items-center px-2 py-0.5 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-md">Agreed</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium rounded-md">No</span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500">Interview if shortlisted</span>
                    @if($application->consent_interview)
                    <span class="inline-flex items-center px-2 py-0.5 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-md">Agreed</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium rounded-md">No</span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500">Terms &amp; conditions</span>
                    @if($application->consent_terms)
                    <span class="inline-flex items-center px-2 py-0.5 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-md">Agreed</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium rounded-md">No</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Signature --}}
        @if($application->signature || $application->signature_date)
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Signature</h3>
            <p class="text-xs text-gray-400 mb-0.5">Signed by</p>
            <p class="text-sm font-medium text-gray-800 mb-3">{{ $application->signature ?: '—' }}</p>
            <p class="text-xs text-gray-400 mb-0.5">Date</p>
            <p class="text-sm font-medium text-gray-800">{{ $application->signature_date?->format('M j, Y') ?? '—' }}</p>
        </div>
        @endif

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
