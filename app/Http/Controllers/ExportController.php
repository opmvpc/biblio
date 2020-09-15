<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Support\Str;
use Xianzhe18\BibTexParser\Listener;
use Xianzhe18\BibTexParser\Parser;

class ExportController extends Controller
{
    public function index()
    {
        return view('export.index');
    }

    public function getBibtex()
    {
        $articles = Article::where('pertinence', 3)->get();

        return  $articles->reduce(function ($text, $article) {
            $bibtex = '';

            if ($article->bibtex !== null) {
                $bibtex = $this->formatBibtexNotNull($article);
            } else {
                $bibtex = $this->formatBibtexNull($article);
            }

            $bibtex = nl2br($bibtex);

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

        $bibtex[0]['citation-key'] = $this->getReference($article);
        $bibtex[0]['month'] = $article->date->format('m');

        return $parser->convertIntoBibTex($bibtex);
    }

    public function formatBibtexNull(Article $article): string
    {
        $parser = new Parser();

        $bibtex[] = [
            'citation-key' => $this->getReference($article),
            'year' => $article->date->format('Y'),
            'month' => $article->date->format('m'),
            'title' => $article->titre,
            'author' => $article->auteurs->pluck('nom')->implode(', '),
            'date' => $article->date->format('Y-m-d'),
            'url' => $article->url,
            'abstract' => $article->abstract,
            'keywords' => $article->keywords->pluck('nom')->implode(', '),
            'doi' => $article->doi,
            'type' => $article->type->nom,
        ];

        return $parser->convertIntoBibTex($bibtex);
    }

    public function getReference($article)
    {
        $reference = '';
        if ($article->reference != null) {
            $reference = $article->reference;
        } elseif ($article->doi != null) {
            $reference = preg_replace('/\(|\)/', '', $article->doi);
        } else {
            $reference = Str::slug(substr($article->titre, 0, 50));
            $reference = preg_replace('/\(|\)/', '', $reference);
        }

        return $reference;
    }
}
