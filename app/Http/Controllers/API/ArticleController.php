<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $articles = Article::where('title', 'like', "%$keyword%")
            ->orWhereHas('category', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->get();

        return response()->json([
            'data' => $articles,
        ]);
    }

    public function summary(Request $request)
    {
        $title = $request->input('title');

        $article = Article::where('title', 'like', "%$title%")->firstOrFail();

        return response()->json(['title' => $article->title, 'konten' => $article->konten]);
    }
}
