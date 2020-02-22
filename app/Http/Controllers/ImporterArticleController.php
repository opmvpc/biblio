<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Services\ImporterBibtexService;
use App\Http\Requests\ImporterArticleRequest;

class ImporterArticleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        $articles = Article
            ::select(['id', 'titre', 'reference'])
            ->get()
            ;

        return view('articles.importer', compact('articles'));
    }

    public function store(ImporterArticleRequest $request)
    {
        (new ImporterBibtexService($request->bibtex))->save();

        return redirect()
            ->route('articles.index')
            ->with('ok', 'Articles importés!');
    }
}
