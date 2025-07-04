<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function index()
    {
        // ambil semua artikel yang is_published = true
        $articles = Article::where('is_published', true)
            ->with('category')
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'content' => $article->content,
                    'published_at' => $article->published_at,
                    'url' => $article->url,
                    'category' => $article->category?->name,
                ];
            });

        return response()->json([
            'data' => $articles,
        ]);
    }

    public function show($slug = null) : JsonResponse {
        if ($slug) {
            $article = Article::where('slug', $slug)
                ->where('is_published', true)
                ->with('category')
                ->firstOrFail();
        } else {
            // get random single article
            $article = Article::where('is_published', true)
                ->with('category')
                ->inRandomOrder()
                ->firstOrFail();
        }

        return response()->json([
            'data' => $article,
        ]);
    }
}
