<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class CitationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function attachCite(Request $request, Article $article)
    {
        $article->attachCite($request->cite);

        return redirect()
            ->back()
            ->with('ok', 'Citations ajoutées!');
    }

    public function attachEstCitePar(Request $request, Article $article)
    {
        $article->attachEstCite($request->cite_par);

        return redirect()
            ->back()
            ->with('ok', 'Citations ajoutées!');
    }

    public function detachCite(Request $request, Article $article)
    {
        $article->detachCite($request->query('detachable'));

        return redirect()
            ->back()
            ->with('ok', 'Citations détachée!');
    }

    public function detachEstCitePar(Request $request, Article $article)
    {
        $article->detachEstCite($request->query('detachable'));

        return redirect()
            ->back()
            ->with('ok', 'Citations détachée!');
    }
}
