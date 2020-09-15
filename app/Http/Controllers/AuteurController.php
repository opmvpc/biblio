<?php

namespace App\Http\Controllers;

use App\Article;
use App\Auteur;
use App\Http\Requests\AuteurRequest;
use Illuminate\Http\Request;

class AuteurController extends Controller
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
        $auteurs = Auteur
            ::withCount('articles')
            ->orderBy('articles_count', 'Desc')
            ->when($request->has('recherche'), function ($query) {
                $query->where('nom', 'LIKE', '%'.request()->recherche.'%');
            })
            ->paginate(10)
            ->appends($request->query())
            ;

        return view('auteurs.index', compact('auteurs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('auteurs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AuteurRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AuteurRequest $request): \Illuminate\Http\RedirectResponse
    {
        $auteur = Auteur::create($request->all());

        return redirect()
            ->route('auteurs.edit', $auteur)
            ->with('ok', 'Auteur ajouté!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Auteur  $auteur
     *
     * @return \Illuminate\View\View
     */
    public function show(Auteur $auteur): \Illuminate\View\View
    {
        $articles = $auteur
            ->articles()
            ->orderBy('date', 'Desc')
            ->paginate(10);

        return view('auteurs.show', compact('auteur', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Auteur  $auteur
     *
     * @return \Illuminate\View\View
     */
    public function edit(Auteur $auteur): \Illuminate\View\View
    {
        return view('auteurs.edit', compact('auteur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AuteurRequest $request
     * @param \App\Auteur  $auteur
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AuteurRequest $request, Auteur $auteur): \Illuminate\Http\RedirectResponse
    {
        $auteur->update($request->all());

        return redirect()
            ->back()
            ->with('ok', 'Auteur modifié!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Auteur  $auteur
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Auteur $auteur): \Illuminate\Http\RedirectResponse
    {
        $auteur->delete();

        return redirect()
            ->route('auteurs.index')
            ->with('ok', 'Auteur supprimé');
    }

    public function attachAuteur(Request $request, Article $article)
    {
        $article->attachAuteurs($request->auteurs);

        return redirect()
            ->to(route('articles.edit', $article) .'#form-auteurs')
            ->with('ok', 'Auteurs attachés!');
    }

    public function detachAuteur(Request $request, Article $article)
    {
        $article->detachAuteur($request->query('auteur'));

        return redirect()
            ->to(route('articles.edit', $article) .'#form-auteurs')
            ->with('ok', 'Auteur détaché!');
    }
}
