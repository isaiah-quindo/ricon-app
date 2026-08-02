@extends('layouts.registration')

@section('title', 'Group Registration')
@section('og_title', 'Group Registration — The Great Cordillera 100 Ultra Trail')
@section('og_description', 'Register your team for The Great Cordillera 100 Ultra Trail. Groups of 5 save 5%, groups of 10 save 10%.')
@section('heading', 'Group Registration')
@section('subheading', 'Sign up your whole team in one go. Groups of 5 save 5%, groups of 10 or more save 10%.')

@section('content')

<form method="POST" action="{{ route('registration.group.store') }}" enctype="multipart/form-data"
    x-data="registrationForm()"
    x-init="init()"
    @submit="submitting = true"
    class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    @csrf

    {{-- ============================================================ --}}
    {{-- MAIN COLUMN — participants                                   --}}
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

        {{-- How it works --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 mb-2">How group registration works</p>
                    <ul class="text-sm text-gray-600 space-y-1.5">
                        <li class="flex items-start gap-2">
                            <span class="text-green-600 font-bold flex-shrink-0">5%</span>
                            <span>off every entry once you have {{ $minParticipants }} participants.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-600 font-bold flex-shrink-0">10%</span>
                            <span>off every entry at 10 participants or more.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Everyone can race a different distance. One payment covers the whole group, and each person gets their own bib and confirmation email.</span>
                        </li>
                    </ul>
                    <p class="text-xs text-gray-400 mt-3">
                        The discount is applied automatically. Discount codes are not used for group registration.
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-4 pt-3">
                <a href="{{ route('registration.create') }}"
                    class="text-sm text-orange-600 hover:text-orange-700 font-medium inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Registering fewer than {{ $minParticipants }} people? Use individual registration
                </a>
            </div>
        </div>

        @include('registration.partials._organizer')

        {{-- flex gap, not space-y: the x-for <template> is a sibling element and
             space-y-* would push the first card down by one gap. --}}
        <div class="flex flex-col gap-4">

            <template x-for="(p, i) in participants" :key="p._id">
                @include('registration.partials._participant_card', ['collapsible' => true])
            </template>

            {{-- Add participant --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <button type="button" @click="addParticipant()"
                    :disabled="!canAdd"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 border-2 border-dashed border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:border-orange-400 hover:text-orange-600 hover:bg-orange-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:border-gray-300 disabled:hover:text-gray-700 disabled:hover:bg-transparent">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add another person
                </button>
                <p x-show="nextTier" x-cloak class="text-sm text-gray-500">
                    Add <span class="font-semibold text-gray-900" x-text="nextTier?.needed"></span>
                    more <span x-text="nextTier?.needed === 1 ? 'person' : 'people'"></span>
                    to unlock <span class="font-semibold text-green-600" x-text="nextTier?.percentage + '% off'"></span>.
                </p>
                <p x-show="!canAdd" x-cloak class="text-sm text-gray-500">
                    Maximum <span x-text="maxParticipants"></span> people per submission. Contact us for larger groups.
                </p>
            </div>

            <p x-show="!canRemove" x-cloak class="text-xs text-gray-400">
                A group needs at least <span x-text="minParticipants"></span> participants.
            </p>
        </div>

        @include('registration.partials._payment')
        @include('registration.partials._agreements')

        {{-- Review trigger --}}
        <div class="flex flex-col items-center gap-3 pb-24 lg:pb-0">
            <button type="button" @click="showReview()"
                class="w-full sm:w-auto px-8 py-3.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-600/20">
                Review Our Registration
            </button>
            <p class="text-xs text-gray-400 text-center max-w-sm">
                Your registration will be reviewed and approved once payment is verified.
                Each participant is emailed separately.
            </p>
        </div>

        @endif
    </div>

    @if($categories->isNotEmpty())
    @include('registration.partials._summary', ['showDiscountCode' => false, 'showTierProgress' => true])
    @endif

    @include('registration.partials._review')
    @include('registration.partials._size_guide_modal')
    @include('registration.partials._waiver_modal')

</form>

@endsection

@push('scripts')
@include('registration.partials._form_script', [
    'minParticipants'     => $minParticipants,
    'maxParticipants'     => $maxParticipants,
    'initialParticipants' => $initialParticipants,
    'allowDiscountCode'   => false,
    'collectOrganizer'    => true,
])
@endpush
