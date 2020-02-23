<?php

namespace App\Http\Controllers;

use App\Article;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualisationController extends Controller
{
    public function index()
    {
        return view('visualisations.index');
    }

    public function articles()
    {

    /*{
    "nodes" :[{"name":"","category":"","id":""},{}],
    "links":[{"source":1,"target":0,"value":1},{}]
    }*/
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

    public function dataArticle()
    {
        $articles = Article
            ::withCount(['estCite'])
            ->select('titre as name','id as index')
            ->get()
            ->each(function($link){
                return $link->group = 1;
            })
            ;
        $links = DB::table('cite')
            ->select('citeur_id AS source','cite_id AS target')
            ->get()
            ->each(function($link){
                return $link->value = 1;
            })
            ;
        $response = [
            'nodes' => $articles,
            'links' => $links,
        ];
        return response()->json($response,200);
    }
}
