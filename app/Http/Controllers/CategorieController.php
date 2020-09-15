<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Http\Requests\CategorieRequest;

class CategorieController extends Controller
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
    public function index(): \Illuminate\View\View
    {
        $categories = Categorie
            ::withCount('articles')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategorieRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategorieRequest $request): \Illuminate\Http\RedirectResponse
    {
        $category = Categorie::create($request->all());

        return redirect()
            ->route('categories.edit', $category)
            ->with('ok', 'Catégorie ajoutée!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Categorie  $categorie
     *
     * @return \Illuminate\View\View
     */
    public function show(Categorie $category): \Illuminate\View\View
    {
        $articles = $category
        ->articles()
        ->orderBy('date', 'Desc')
        ->paginate(10);

        return view('categories.show', compact('category', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Categorie  $categorie
     *
     * @return \Illuminate\View\View
     */
    public function edit(Categorie $category): \Illuminate\View\View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategorieRequest $request
     * @param \App\Categorie  $categorie
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategorieRequest $request, Categorie $category): \Illuminate\Http\RedirectResponse
    {
        $category->update($request->all());

        return redirect()
            ->back()
            ->with('ok', 'Catégorie modifiée!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Categorie  $categorie
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Categorie $category): \Illuminate\Http\RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('ok', 'Catégorie supprimée');
    }
}
