@extends('layouts.registration')

@section('title', 'Register')
@section('og_title', 'Register for RICON — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Sign up for The Great Cordillera 100 Ultra Trail. Choose your distance: 10 KM, 21 KM, 60 KM, or 100 KM.')
@section('heading', 'Race Registration')
@section('subheading', 'Fill out the form below to register. You will receive a confirmation once your payment is verified.')

@section('content')

<form method="POST" action="{{ route('registration.store') }}" enctype="multipart/form-data"
    x-data="registrationForm()"
    x-init="init()"
    @submit="submitting = true"
    class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    @csrf

    {{-- ============================================================ --}}
    {{-- MAIN COLUMN — your details                                   --}}
    {{-- ============================================================ --}}
    <div x-show="!reviewing" class="lg:col-span-2 flex flex-col gap-6">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <div class="space-y-0.5">
                    <p class="text-sm font-semibold text-red-800 mb-1">Please fix the following:</p>
                    @foreach($errors->all() as $error)
                    <p class="text-sm text-red-800">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div x-show="showClientErrorBanner" x-cloak class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-red-800" x-text="clientErrorMessage"></p>
            </div>
        </div>

        @if($categories->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <p class="text-sm text-gray-500">No race categories are currently available.</p>
        </div>
        @else

        {{-- Group registration hand-off --}}
        <div class="bg-white border border-orange-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">Registering as a group?</p>
                <p class="text-sm text-gray-500 mt-0.5">
                    Sign up 5 or more people together and save 5%, or 10% at 10 people. One payment covers everyone.
                </p>
            </div>
            <a href="{{ route('registration.group.create') }}"
                class="py-3 px-8 inline-flex items-center justify-center gap-x-2 text-sm font-bold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition-colors flex-shrink-0">
                Group registration
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- The single participant. Not collapsible: there is nothing to collapse past. --}}
        <div class="flex flex-col gap-4">
            <template x-for="(p, i) in participants" :key="p._id">
                @include('registration.partials._participant_card', ['collapsible' => false])
            </template>
        </div>

        @include('registration.partials._payment')
        @include('registration.partials._agreements')

        {{-- Review trigger --}}
        <div class="flex flex-col items-center gap-3 pb-24 lg:pb-0">
            <button type="button" @click="showReview()"
                class="w-full sm:w-auto px-8 py-3.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-600/20">
                Review My Registration
            </button>
            <p class="text-xs text-gray-400 text-center max-w-sm">
                Your registration will be reviewed and approved once payment is verified. You will be notified by email.
            </p>
        </div>

        @endif
    </div>

    @if($categories->isNotEmpty())
    @include('registration.partials._summary', ['showDiscountCode' => true, 'showTierProgress' => false])
    @endif

    @include('registration.partials._review')
    @include('registration.partials._size_guide_modal')
    @include('registration.partials._waiver_modal')

</form>

@endsection

@push('scripts')
@include('registration.partials._form_script', [
    'minParticipants'     => 1,
    'maxParticipants'     => 1,
    'initialParticipants' => 1,
    'allowDiscountCode'   => true,
])
@endpush
