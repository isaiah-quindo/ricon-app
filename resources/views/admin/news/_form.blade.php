{{-- Shared fields for create/edit. Expects optional $post. --}}

<div class="space-y-5">
    {{-- Title --}}
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title"
               value="{{ old('title', $post->title ?? '') }}" required
               placeholder="e.g. Route change announcement"
               class="w-full rounded-lg border {{ $errors->has('title') ? 'border-red-400' : 'border-gray-200' }} text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
        @error('title')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Cover image --}}
    <div>
        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1.5">Cover Image</label>
        @if(isset($post) && $post->cover_image_url)
            <div class="mb-3">
                <img src="{{ $post->cover_image_url }}" alt="Current cover image"
                     class="w-48 rounded-lg border border-gray-200 object-cover">
                <p class="text-xs text-gray-400 mt-1">Current cover. Upload a new file to replace it.</p>
            </div>
        @endif
        <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp"
               class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 file:cursor-pointer" />
        <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP, up to 5MB. Recommended size: 1200×630px. Shown on the news page card, article header, and social media share previews.</p>
        @error('cover_image')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Body (Trix) --}}
    <div>
        <label for="body" class="block text-sm font-medium text-gray-700 mb-1.5">
            Body <span class="text-red-500">*</span>
        </label>
        <input id="body" type="hidden" name="body" value="{{ old('body', $post->body ?? '') }}">
        <div data-upload-url="{{ route('admin.news.uploadImage') }}"
             class="news-editor overflow-hidden rounded-lg border {{ $errors->has('body') ? 'border-red-400' : 'border-gray-200' }} focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent">
            <trix-editor input="body" class="trix-content min-h-[16rem] px-3.5 py-2.5 text-sm"></trix-editor>
        </div>
        <p class="text-xs text-gray-400 mt-1">Drag and drop images into the editor to embed them inline.</p>
        @error('body')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Publish toggle --}}
    <div>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="publish" value="0">
            <input type="checkbox" name="publish" value="1"
                   {{ old('publish', isset($post) && $post->published_at !== null) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <div>
                <span class="text-sm font-medium text-gray-700">Published</span>
                <p class="text-xs text-gray-400">Visible on the public news page. Leave unchecked to save as a draft.</p>
            </div>
        </label>
    </div>
</div>

@push('scripts')
    @vite('resources/js/news-editor.js')
@endpush
