<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        $posts = NewsPost::published()
            ->orderByDesc('published_at')
            ->simplePaginate(9);

        return view('news.index', compact('posts'));
    }

    public function show(NewsPost $newsPost)
    {
        abort_unless($newsPost->is_published, 404);

        return view('news.show', compact('newsPost'));
    }
}
