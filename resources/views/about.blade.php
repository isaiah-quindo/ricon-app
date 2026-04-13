@extends('layouts.public')
@section('title', 'About Us')

@section('content')

{{-- ========================================================
         PAGE HEADER
    ======================================================== --}}
<section class="bg-[#0d0d0d] pt-36 pb-16 text-center">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold mb-2 uppercase tracking-wider">Our Story</p>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">About Us</h1>
        <p class="text-gray-400 max-w-xl mx-auto leading-relaxed">
            Learn about RiCon and the people behind The Great Cordillera 100.
        </p>
    </div>
</section>

{{-- ========================================================
         TWO-COLUMN CONTENT
    ======================================================== --}}
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">

            {{-- LEFT COLUMN — Portrait video placeholder --}}
            <div class="flex justify-center md:justify-center">
                <div class="w-full max-w-sm aspect-[9/16] bg-[#1a1a1a] rounded-2xl border border-white/10 flex flex-col items-center justify-center gap-3 text-gray-600 select-none">
                    <svg class="w-14 h-14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9A2.25 2.25 0 004.5 18.75z" />
                    </svg>
                    <p class="text-sm">Video Placeholder</p>
                </div>
            </div>

            {{-- RIGHT COLUMN — About RiCon & Founder --}}
            <div class="space-y-16">

                {{-- About RiCon --}}
                <div>
                    <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">Who We Are</p>
                    <h2 class="text-3xl font-bold text-white mb-4">About RiCon</h2>
                    <div class="space-y-4 text-gray-400 leading-relaxed">
                        <p>
                            RiCON is a race organization focused on producing endurance events across Philippine terrain.
                        </p>
                        <p>
                            The organization operates under the philosophy of Running in Constant, emphasizing discipline, progression, and respect for the endurance journey.
                        </p>
                        <p>
                            RiCON aims to elevate the quality of endurance racing experiences in the Philippines through thoughtful course design and athlete-centered events.
                        </p>
                    </div>
                </div>

                {{-- Divider --}}
                <hr class="border-white/10">

                {{-- About the Founder --}}
                <div>
                    <p class="text-orange-500 text-xs font-semibold uppercase tracking-wider mb-2">The Person Behind It</p>
                    <h2 class="text-3xl font-bold text-white mb-6">About the Founder</h2>

                    {{-- Founder card --}}
                    <div class="flex items-start gap-5">
                        {{-- Avatar placeholder --}}

                        <div>
                            <p class="text-white font-bold text-lg">Don T. Santillan</p>
                            <p class="text-orange-500 text-sm mb-3">Founder & Race Director</p>
                            <div class="space-y-3 text-gray-400 leading-relaxed text-sm">
                                <p>
                                    I started my running journey in 2012 and co-founded the Don’t Stop Running Community. As an active member of PhilTRA and a member of the Asia Skyrunning Council since 2026, I bring extensive ultrarunning experience to race organization, having completed numerous 100-kilometer and 100-mile races.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection