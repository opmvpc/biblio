<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Services\ImporterDoiService;
use App\Services\ImporterBibtexService;
use Illuminate\Support\Facades\Validator;
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
            ->orderBy('titre', 'asc')
            ->get()
            ;

        return view('articles.importer', compact('articles'));
    }

    public function store(ImporterArticleRequest $request)
    {
        if ($request->has('doi')) {
            // dd((new ImporterDoiService($request->doi))->getBibtex());
            try {
                (new ImporterDoiService($request->doi))->save();
            } catch (\InvalidArgumentException $e) {
                $validator = Validator::make($request->only('doi'), []);
                $validator->errors()->add('doi', 'Le doi est invalide');
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);
            }
        } elseif ($request->has('bibtex')) {
            (new ImporterBibtexService($request->bibtex))->save();
        }


        return redirect()
            ->route('articles.index')
            ->with('ok', 'Articles importés!');
    }
}
