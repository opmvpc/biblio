<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Xianzhe18\BibTexParser\Parser;
use Xianzhe18\BibTexParser\Listener;

class ExportController extends Controller
{
    public function index()
    {
        return view('export.index');
    }

    public function getBibtex()
    {
        $articles = Article::where('pertinence', 3)->get();
        // dump($articles);

        return  $articles->reduce(function ($text, $article) {
            $bibtex = '';

            if ($article->bibtex !== null) {
                $bibtex = $this->formatBibtexNotNull($article);
            }

            return  $text .'<br><br>'. $bibtex;
        });
    }

    public function formatBibtexNotNull(Article $article): string
    {
        $parser = new Parser();
        $listener = new Listener();
        $parser->addListener($listener);
        $parser->parseString($article->bibtex);
        $bibtex = $listener->export();

        $reference = '';
        if ($article->reference != null) {
            $reference = $article->reference;
        } elseif ($article->doi != null) {
            $reference = preg_replace('/\(|\)/', '', $article->doi);
        } else {
            $reference = Str::slug(substr($article->titre, 0, 50));
            $reference = preg_replace('/\(|\)/', '', $reference);
        }
        $bibtex[0]['citation-key'] = $reference;
        $bibtex[0]['month'] = $article->date->format('m');

        return $parser->convertIntoBibTex($bibtex);
    }
}
