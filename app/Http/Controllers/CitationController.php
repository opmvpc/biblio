<?php

namespace App\Http\Controllers;

use App\Article;
use App\Services\ImporterDoiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function attachCite(Request $request, Article $article)
    {
        if ($request->has('import_doi_cite') && $request->import_doi_cite) {
            try {
                (new ImporterDoiService($request->import_doi_cite))->save();
                $article->attachCite([Article::where('doi', strtolower($request->import_doi_cite))->get()->first()->id]);
            } catch (\InvalidArgumentException $e) {
                $validator = Validator::make($request->only('doi'), []);
                $validator->errors()->add('import_doi_cite', 'Le doi est invalide');

                return redirect()
                    ->to(route('articles.edit', $article) .'#inp-import_doi_cite')
                    ->withInput()
                    ->withErrors($validator);
            }
        } elseif ($request->has('cite')) {
            $article->attachCite($request->cite);
        }

        return redirect()
            ->to(route('articles.edit', $article) .'#form-references')
            ->with('ok', 'Références attachées!');
    }

    public function attachEstCitePar(Request $request, Article $article)
    {
        if ($request->has('import_doi_cite_par') && $request->import_doi_cite_par) {
            try {
                (new ImporterDoiService($request->import_doi_cite_par))->save();
                $article->attachEstCite([Article::where('doi', strtolower($request->import_doi_cite_par))->get()->first()->id]);
            } catch (\InvalidArgumentException $e) {
                $validator = Validator::make($request->only('doi'), []);
                $validator->errors()->add('import_doi_cite_par', 'Le doi est invalide');

                return redirect()
                    ->to(route('articles.edit', $article) .'#inp-import_doi_cite_par')
                    ->withInput()
                    ->withErrors($validator);
            }
        } elseif ($request->has('cite_par')) {
            $article->attachEstCite($request->cite_par);
        }

        return redirect()
            ->to(route('articles.edit', $article) .'#form-citations')
            ->with('ok', 'Citations attachées!');
    }

    public function detachCite(Request $request, Article $article)
    {
        $article->detachCite($request->query('detachable'));

        return redirect()
            ->to(route('articles.edit', $article) .'#form-references')
            ->with('ok', 'Référence détachée!');
    }

    public function detachEstCitePar(Request $request, Article $article)
    {
        $article->detachEstCite($request->query('detachable'));

        return redirect()
            ->to(route('articles.edit', $article) .'#form-citations')
            ->with('ok', 'Citation détachée!');
    }
}
