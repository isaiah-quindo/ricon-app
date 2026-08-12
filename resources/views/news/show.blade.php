@extends('layouts.public')

@section('title', $newsPost->title)
@section('og_type', 'article')
@section('og_title', $newsPost->title . ' — RICON')
@section('og_description', $newsPost->excerpt)

@if($newsPost->cover_image_url)
    @section('og_image', $newsPost->cover_image_url)
@endif

@section('content')

{{-- Hero --}}
<section class="bg-[#0d0d0d] pt-36 pb-16 text-center">
    <div class="mx-auto px-8" style="max-width:768px;">
        <p class="text-orange-500 text-sm font-semibold uppercase tracking-wider mb-4">News</p>
        <h1 class="text-3xl md:text-5xl font-black leading-tight">{{ $newsPost->title }}</h1>
        <p class="text-gray-500 text-sm mt-6">{{ $newsPost->published_at->format('F j, Y') }}</p>
    </div>
</section>

{{-- Article --}}
<section class="bg-[#111111] py-16 md:py-24">
    <div class="mx-auto px-8 news-article" style="max-width:768px;">

        @if($newsPost->cover_image_url)
            <img src="{{ $newsPost->cover_image_url }}" alt="{{ $newsPost->title }}"
                 class="w-full rounded-2xl mb-12 border border-white/10">
        @endif

        <article class="trix-content">
            {!! $newsPost->body !!}
        </article>

        <div class="mt-16 pt-8 border-t border-white/10">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-2 text-orange-500 hover:text-orange-400 text-sm font-semibold transition-colors">
                ← Back to News
            </a>
        </div>

    </div>
</section>

@endsection
