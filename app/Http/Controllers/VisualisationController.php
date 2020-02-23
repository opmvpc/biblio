<?php

namespace App\Http\Controllers;

use App\Article;
use App\Keyword;
use Illuminate\Http\Request;

class VisualisationController extends Controller
{
    public function index()
    {
        return view('visualisations.index');
    }

    public function articles()
    {
        $articles = Article
            ::with(['cite', 'estCite', 'categories', 'keywords'])
            ->withCount(['cite', 'estCite', 'categories', 'keywords'])
            ->orderBy('date', 'desc')
            ->get()
            ;

        return view('visualisations.articles', compact('articles'));
    }

    public function keywords()
    {
        $keywords = Keyword
            ::with(['articles'])
            ->withCount(['articles'])
            ->orderBy('nom', 'asc')
            ->get()
            ;

        return view('visualisations.keywords', compact('keywords'));
    }
}
