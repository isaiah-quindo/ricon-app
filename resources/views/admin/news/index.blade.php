@extends('layouts.admin')
@section('title', 'News')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</p>
    <a href="{{ route('admin.news.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Post
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Published</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($post->cover_image_url)
                                <img src="{{ $post->cover_image_url }}" alt=""
                                     class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $post->title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $post->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($post->is_published)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Published
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-full px-2.5 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $post->published_at?->format('M j, Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $post->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($post->is_published)
                                <a href="{{ route('news.show', $post) }}" target="_blank"
                                   class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                    View
                                </a>
                            @endif
                            <a href="{{ route('admin.news.edit', $post) }}"
                               class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.news.destroy', $post) }}"
                                  onsubmit="return confirm('Delete \'{{ $post->title }}\'? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <p class="text-sm">No news posts yet.</p>
                            <a href="{{ route('admin.news.create') }}"
                               class="text-indigo-600 hover:underline text-sm font-medium">
                                Write the first post →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($posts->hasPages())
<div class="mt-4">
    {{ $posts->links() }}
</div>
@endif

@endsection
