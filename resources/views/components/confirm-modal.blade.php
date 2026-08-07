@props([
    'action',
    'method'       => 'POST',
    'trigger'      => 'Confirm',
    'triggerClass' => 'w-full px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors',
    'title'        => 'Are you sure?',
    'message'      => '',
    'confirmLabel' => 'Confirm',
    'tone'         => 'green',
])

@php
    $tones = [
        'green' => ['bg-green-600 hover:bg-green-700', 'bg-green-100 text-green-600'],
        'red'   => ['bg-red-600 hover:bg-red-700', 'bg-red-100 text-red-600'],
    ];
    [$confirmClasses, $iconClasses] = $tones[$tone] ?? $tones['green'];
@endphp

<div x-data="{ open: false }">
    <button type="button" @click="open = true" class="{{ $triggerClass }}">
        {{ $trigger }}
    </button>

    <div x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="open = false"
        role="dialog" aria-modal="true">

        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
            @click="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <div class="p-6">
                <div class="flex items-start gap-4">
                    <span class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $iconClasses }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            @if($tone === 'red')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            @endif
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-base font-bold text-gray-900">{{ $title }}</h3>
                        @if($message)
                        <p class="text-sm text-gray-500 mt-1.5">{{ $message }}</p>
                        @endif
                        {{-- Optional extra detail, e.g. a list of what is about to change. --}}
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex items-center justify-end gap-3">
                <button type="button" @click="open = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancel
                </button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @if(strtoupper($method) !== 'POST')
                    @method($method)
                    @endif
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors {{ $confirmClasses }}">
                        {{ $confirmLabel }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
