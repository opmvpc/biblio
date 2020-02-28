<?php

namespace App\Http\Controllers;

use App\Auteur;
use App\Article;
use App\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualisationController extends Controller
{
    public function index()
    {
        return redirect()->route('visualisations.articles');
    }

    public function articles()
    {

        return view('visualisations.articles');
    }

    public function dataArticles()
    {
        $nodes = Article
            ::withCount(['estCite'])
            ->get()
            ;

        $edges = DB::table('cite')
            ->join('articles AS citation', 'citation.id', '=', 'citeur_id')
            ->join('articles AS reference', 'reference.id', '=', 'cite_id')
            ->select('citation.slug AS source','reference.slug AS target')
            ->get()
            ;

        $response = [
            'nodes' => $nodes,
            'edges' => $edges,
        ];

        return response()->json($response,200);
    }

    public function auteurs()
    {

        return view('visualisations.auteurs');
    }

    public function dataAuteurs()
    {
        $nodes = Article
            ::withCount(['estCite'])
            ->get()
            ->each( function ($article) {
                $article->nodeType = 'Articles';
            })
            ;

        $auteurs = Auteur
            ::withCount(['articles'])
            ->get()
            ->each( function ($auteur) {
                $auteur->nodeType = 'Auteurs';
            })
            ;

        $nodes = $nodes->union($auteurs);

        $edges = DB::table('article_auteur')
            ->join('articles', 'articles.id', '=', 'article_id')
            ->join('auteurs', 'auteurs.id', '=', 'auteur_id')
            ->select('articles.slug AS source','auteurs.slug AS target')
            ->get()
            ;

        // dd($edges);
        $response = [
            'nodes' => $nodes,
            'edges' => $edges,
        ];


        return response()->json($response,200);
    }

    public function keywords()
    {
        return view('visualisations.keywords');
    }

}
