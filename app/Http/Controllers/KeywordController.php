<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\KeywordRequest;
use App\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $keywords = Keyword
            ::withCount('articles')
            ->orderBy('articles_count', 'Desc')
            ->when($request->has('recherche'), function ($query) {
                $query->where('nom', 'LIKE', '%'.request()->recherche.'%');
            })
            ->paginate(10)
            ->appends($request->query())
            ;

        return view('keywords.index', compact('keywords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('keywords.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param KeywordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(KeywordRequest $request): \Illuminate\Http\RedirectResponse
    {
        $keyword = Keyword::create($request->all());

        return redirect()
            ->route('keywords.edit', $keyword)
            ->with('ok', 'Mot-clé ajouté!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Keyword  $keyword
     *
     * @return \Illuminate\View\View
     */
    public function show(Keyword $keyword): \Illuminate\View\View
    {
        $articles = $keyword
            ->articles()
            ->orderBy('date', 'Desc')
            ->paginate(10);

        return view('keywords.show', compact('keyword', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Keyword  $keyword
     *
     * @return \Illuminate\View\View
     */
    public function edit(Keyword $keyword): \Illuminate\View\View
    {
        return view('keywords.edit', compact('keyword'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param KeywordRequest $request
     * @param \App\Keyword  $keyword
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(KeywordRequest $request, Keyword $keyword): \Illuminate\Http\RedirectResponse
    {
        $keyword->update($request->all());

        return redirect()
            ->back()
            ->with('ok', 'Mot-clé modifié!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Keyword  $keyword
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Keyword $keyword): \Illuminate\Http\RedirectResponse
    {
        $keyword->delete();

        return redirect()
            ->route('keywords.index')
            ->with('ok', 'Mot-clé supprimé');
    }

    public function attachKeyword(Request $request, Article $article)
    {
        // dd($request->keywords);
        $article->attachKeywords($request->keywords);

        return redirect()
            ->to(route('articles.edit', $article) .'#form-keywords')
            ->with('ok', 'Mots-clés attachés!');
    }

    public function detachKeyword(Request $request, Article $article)
    {
        $article->detachKeyword($request->query('keyword'));

        return redirect()
            ->to(route('articles.edit', $article) .'#form-keywords')
            ->with('ok', 'Mot-clé détaché!');
    }
}
