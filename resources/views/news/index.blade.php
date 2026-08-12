@extends('layouts.public')

@section('title', 'News')
@section('og_title', 'News — RICON')
@section('og_description', 'Latest updates and announcements from The Great Cordillera 100 Ultra Trail.')

@section('content')

{{-- Hero --}}
<section class="bg-[#0d0d0d] pt-36 pb-16 text-center">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-4">Latest Updates</p>
        <h1 class="text-4xl md:text-5xl font-black">News</h1>
    </div>
</section>

{{-- Posts --}}
<section class="bg-[#111111] py-24">
    <div class="mx-auto px-8" style="max-width:1280px;">

        @if($posts->isEmpty())
            <div class="text-center py-16">
                <p class="text-gray-400 text-lg">No news yet. Check back soon.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <a href="{{ route('news.show', $post) }}"
                       class="group bg-[#161616] border border-white/10 rounded-2xl overflow-hidden hover:border-orange-600/50 transition-colors flex flex-col">
                        @if($post->cover_image_url)
                            <div class="aspect-[16/9] overflow-hidden">
                                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="aspect-[16/9] bg-gradient-to-br from-orange-900/40 via-[#1a1a1a] to-[#111111] flex items-center justify-center">
                                <img src="/logomark.svg" alt="" class="w-12 h-12 opacity-40">
                            </div>
                        @endif
                        <div class="p-6 flex flex-col gap-3 flex-1">
                            <p class="text-gray-500 text-xs uppercase tracking-wider">{{ $post->published_at->format('F j, Y') }}</p>
                            <h2 class="text-lg font-bold text-white group-hover:text-orange-500 transition-colors">{{ $post->title }}</h2>
                            <p class="text-gray-400 text-sm leading-relaxed">{{ $post->excerpt }}</p>
                            <span class="mt-auto pt-2 text-orange-500 text-sm font-semibold">Read more →</span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="flex items-center justify-center gap-4 mt-16">
                    @if($posts->onFirstPage())
                        <span class="py-2.5 px-5 text-sm font-medium rounded-lg border border-white/10 text-gray-600 cursor-not-allowed">← Newer</span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}"
                           class="py-2.5 px-5 text-sm font-medium rounded-lg border border-white/20 text-gray-300 hover:text-white hover:border-white/50 transition-colors">← Newer</a>
                    @endif

                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}"
                           class="py-2.5 px-5 text-sm font-medium rounded-lg border border-white/20 text-gray-300 hover:text-white hover:border-white/50 transition-colors">Older →</a>
                    @else
                        <span class="py-2.5 px-5 text-sm font-medium rounded-lg border border-white/10 text-gray-600 cursor-not-allowed">Older →</span>
                    @endif
                </div>
            @endif
        @endif

    </div>
</section>

@endsection
