<?php

namespace App\Http\Controllers;

use App\Auteur;
use App\Article;
use App\Keyword;
use App\Categorie;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Article
            ::with('categories')
            ->orderBy('date', 'Desc')
            ->when($request->has('categorie') && $request->categorie != null, function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('slug', 'LIKE', '%'.request()->categorie.'%');
                });
            })
            ->when($request->has('recherche'), function ($query) {
                $query->where('titre', 'LIKE', '%'.request()->recherche.'%')
                ->orWhere('resume', 'LIKE', '%'.request()->recherche.'%')
                ->orWhere('date', 'LIKE', '%'.request()->recherche.'%')
                ->orWhere('abstract', 'LIKE', '%'.request()->recherche.'%')
                ->orWhere('doi', 'LIKE', '%'.request()->recherche.'%')
                ->orWhere('reference', 'LIKE', '%'.request()->recherche.'%')
                ->orWhereHas('categories', function ($query) {
                    $query->where('nom', 'LIKE', '%'.request()->recherche.'%');
                })
                ->orWhereHas('keywords', function ($query) {
                    $query->where('nom', 'LIKE', '%'.request()->recherche.'%');
                })
                ->orWhereHas('auteurs', function ($query) {
                    $query->where('nom', 'LIKE', '%'.request()->recherche.'%');
                });
            })
            ->paginate(10)
            ->appends($request->query())
            ;

        $categories = Categorie::pluck('nom', 'slug');

        return view('articles.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categorie::pluck('nom', 'id');

        return view('articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        Article::create($request->all());

        return redirect()
            ->route('articles.index')
            ->with('ok', 'Article ajouté!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $keywords = $article
            ->keywords()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->get();

        $auteurs = $article
            ->auteurs()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->get();

        return view('articles.show', compact('article', 'keywords', 'auteurs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $categories = Categorie::pluck('nom', 'id');

        $keywordsList = Keyword
            ::whereNotIn('id', $article->keywords->pluck('id'))
            ->pluck('nom', 'id');

        $auteursList = Auteur
            ::whereNotIn('id', $article->auteurs->pluck('id'))
            ->pluck('nom', 'id');

        $auteurs = $article
            ->auteurs()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->get();

        $keywords = $article
            ->keywords()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->get();

        $articlesCite = Article
            ::select(['id', 'titre', 'reference'])
            ->where('id', '<>', $article->id)
            ->whereNotIn('id', $article->cite->pluck('id'))
            ->orderBy('titre', 'asc')
            ->get()
            ;

        $articlesEstCite = Article
            ::select(['id', 'titre', 'reference'])
            ->where('id', '<>', $article->id)
            ->whereNotIn('id', $article->estCite->pluck('id'))
            ->orderBy('titre', 'asc')
            ->get()
            ;

        return view('articles.edit', compact('article', 'categories', 'articlesCite', 'articlesEstCite', 'keywordsList', 'keywords', 'auteursList', 'auteurs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->update($request->all());
        $article->saveCategories($request->categories);

        return redirect()
            ->route('articles.index')
            ->with('ok', 'Article modifié!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->deletePdfArticle();
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('ok', 'Article supprimé');
    }
}
