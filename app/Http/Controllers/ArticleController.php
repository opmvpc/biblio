<?php

namespace App\Http\Controllers;

use App\Article;
use App\Auteur;
use App\Categorie;
use App\Http\Requests\ArticleRequest;
use App\Keyword;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'api']);
    }

    public function api(Request $request, string $category = null)
    {
        $query = Article
            ::with(['categories', 'auteurs', 'type'])
            ->withCount(['cite', 'estCite'])
            ->when($category && $category != null, function ($query) use ($category) {
                $query->whereHas('categories', function ($query) use ($category) {
                    $query->where('slug', 'LIKE', '%'.$category.'%');
                });
            })
            // ->when(request()->has('date') && request()->has('date') != null, function ($query) {
            //     $query->orderBy('date', request()->has('date'));
            // })
            ->when($request->has('search'), function ($query) {
                $query->where('titre', 'LIKE', '%'.request()->search['value'].'%')
                ->orWhere('resume', 'LIKE', '%'.request()->search['value'].'%')
                ->orWhere('date', 'LIKE', '%'.request()->search['value'].'%')
                ->orWhere('abstract', 'LIKE', '%'.request()->search['value'].'%')
                ->orWhere('doi', 'LIKE', '%'.request()->search['value'].'%')
                ->orWhere('reference', 'LIKE', '%'.request()->search['value'].'%')
                ->orWhereHas('categories', function ($query) {
                    $query->where('nom', 'LIKE', '%'.request()->search['value'].'%');
                })
                ->orWhereHas('keywords', function ($query) {
                    $query->where('nom', 'LIKE', '%'.request()->search['value'].'%');
                })
                ->orWhereHas('auteurs', function ($query) {
                    $query->where('nom', 'LIKE', '%'.request()->search['value'].'%');
                });
            })
            // ->orderBy('date', 'Desc')
            ;

        return DataTables::eloquent($query)->toJson();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $articles = Article
            ::with(['categories', 'auteurs', 'type'])
            ->withCount(['cite', 'estCite'])
            ->when(! $request->has('sort'), function ($query) {
                $query->orderBy('date', 'Desc');
            })
            ->when($request->has('sort') && $request->sort != null, function ($query) {
                $query->orderBy(request()->sort ?? 'date', request()->order ?? 'DESC');
            })
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
                ->orWhereHas('type', function ($query) {
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
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $categories = Categorie::pluck('nom', 'id');
        $pertinences = Article::getPertinenceDatas();

        return view('articles.create', compact('categories', 'pertinences'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ArticleRequest $request): \Illuminate\Http\RedirectResponse
    {
        $article = Article::create($request->all());
        $article->saveCategories($request->categories);

        return redirect()
            ->route('articles.edit', $article)
            ->with('ok', 'Article ajouté!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Article  $article
     *
     * @return \Illuminate\View\View
     */
    public function show(Article $article): \Illuminate\View\View
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
     * @param \App\Article  $article
     *
     * @return \Illuminate\View\View
     */
    public function edit(Article $article): \Illuminate\View\View
    {
        $categories = Categorie::pluck('nom', 'id');
        $pertinences = Article::getPertinenceDatas();

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

        return view('articles.edit', compact('article', 'categories', 'articlesCite', 'articlesEstCite', 'keywordsList', 'keywords', 'auteursList', 'auteurs', 'pertinences'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest $request
     * @param \App\Article  $article
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ArticleRequest $request, Article $article): \Illuminate\Http\RedirectResponse
    {
        $article->update($request->all());
        $article->saveCategories($request->categories);

        return redirect()
            ->back()
            ->with('ok', 'Article modifié!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Article  $article
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Article $article): \Illuminate\Http\RedirectResponse
    {
        $article->deletePdfArticle();
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('ok', 'Article supprimé');
    }
}
