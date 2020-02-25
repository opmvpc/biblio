<?php

namespace App\Http\Controllers;

use App\Auteur;
use App\Article;
use Illuminate\Http\Request;
use App\Http\Requests\AuteurRequest;

class AuteurController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auteurs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuteurRequest $request)
    {
        $auteur = Auteur::create($request->all());

        return redirect()
            ->route('auteurs.edit', $auteur)
            ->with('ok', 'Auteur ajouté!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Auteur  $auteur
     * @return \Illuminate\Http\Response
     */
    public function show(Auteur $auteur)
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
     * @param  \App\Auteur  $auteur
     * @return \Illuminate\Http\Response
     */
    public function edit(Auteur $auteur)
    {
        return view('auteurs.edit', compact('auteur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auteur  $auteur
     * @return \Illuminate\Http\Response
     */
    public function update(AuteurRequest $request, Auteur $auteur)
    {
        $auteur->update($request->all());

        return redirect()
            ->back()
            ->with('ok', 'Auteur modifié!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Auteur  $auteur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auteur $auteur)
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
            ->back()
            ->with('ok', 'Auteurs attachés!');
    }

    public function detachAuteur(Request $request, Article $article)
    {
        $article->detachAuteur($request->query('auteur'));

        return redirect()
            ->back()
            ->with('ok', 'Auteur détaché!');
    }
}
