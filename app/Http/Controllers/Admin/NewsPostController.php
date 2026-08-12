<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;

class NewsPostController extends Controller
{
    public function index()
    {
        $posts = NewsPost::latest()->paginate(20);

        return view('admin.news.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'publish' => 'boolean',
        ]);

        NewsPost::create([
            'title' => $validated['title'],
            'body' => Purify::clean($validated['body']),
            'cover_image_path' => $request->file('cover_image')?->store('news', 's3'),
            'published_at' => $request->boolean('publish') ? now() : null,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'News post created.');
    }

    public function edit(NewsPost $news)
    {
        return view('admin.news.edit', ['post' => $news]);
    }

    public function update(Request $request, NewsPost $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'publish' => 'boolean',
        ]);

        $coverPath = $news->cover_image_path;
        if ($request->hasFile('cover_image')) {
            if ($coverPath) {
                Storage::disk('s3')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('news', 's3');
        }

        // Keep the original publish timestamp when it stays published
        $publishedAt = $request->boolean('publish')
            ? ($news->published_at ?? now())
            : null;

        $news->update([
            'title' => $validated['title'],
            'body' => Purify::clean($validated['body']),
            'cover_image_path' => $coverPath,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'News post updated.');
    }

    public function destroy(NewsPost $news)
    {
        if ($news->cover_image_path) {
            Storage::disk('s3')->delete($news->cover_image_path);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News post deleted.');
    }

    // Trix inline attachment upload target
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('news', 's3');

        return response()->json(['url' => Storage::disk('s3')->url($path)]);
    }
}
