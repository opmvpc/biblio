<?php

namespace App\Services;

use App\Article;
use App\Keyword;
use Carbon\Carbon;
use Illuminate\Support\Str;
use RenanBr\BibTexParser\Parser;
use RenanBr\BibTexParser\Listener;

class ImporterBibtexService
{
    private $bibtex;

    public function __construct(string $bibtex) {
        $this->bibtex = $bibtex;
    }

    public function save(): void
    {
        $articles = $this->parse();
        $this->createArticles($articles);
    }

    private function parse(): array
    {
        $parser = new Parser();
        $listener = new Listener();
        $parser->addListener($listener);
        $parser->parseString($this->bibtex);
        $articles = $listener->export();

        return $articles;
    }

    private function createArticles(array $articles): void
    {
        collect($articles)
        ->map( function ($article) {
            return collect($article);
        })
        ->map( function ($article) {
            return [
                'titre' => $this->sanatizeText($article->get('title')),
                'auteurs' => $this->sanatizeText($article->get('author')),
                'reference' => $this->sanatizeText($article->get('citation-key')),
                'date' => $this->getDate($article->get('year'), $article->get('month')),
                'url' => $this->sanatizeText($article->get('url')),
                'bibtex' => $article->get('"_original'),
                'abstract' => $this->sanatizeText($article->get('abstract')),
                'keywords' => $this->sanatizeText($article->get('keywords')),
                'doi' => $this->sanatizeText($article->get('doi')),
            ];
        })
        ->each( function ($article) {
            $nouvelArticle = Article::firstOrCreate(['titre' => $article['titre']], $article);

            if (request()->has('cite_par')) {
                $nouvelArticle->attachEstCite(request()->cite_par);
            }

            if (request()->has('cite')) {
                $nouvelArticle->attachCite(request()->cite);
            }

            collect(explode(',', $article['keywords']))
                ->map( function ($keyword) {
                    return trim($keyword);
                })
                ->filter()
                ->each( function ($keyword) use ($nouvelArticle) {
                    $nouveauKeyword = Keyword::firstOrCreate(['nom' => $keyword]);
                    $nouvelArticle->attachKeyword($nouveauKeyword->id);
                })
                ;
        })
        ;
    }

    public function bibtex(): string
    {
        return $this->bibtex;
    }

    private function sanatizeText($bibtexAttribute): string
    {
        $sanatyzedText = preg_replace('/{(.*)}/', '$1', $bibtexAttribute);
        $sanatyzedText = Str::limit($sanatyzedText,2000);
        return $sanatyzedText;
    }

    private function getDate(?int $annee, ?int $mois): Carbon
    {
        return Carbon::createFromDate($annee ?? 1975, $mois ?? 1, 1);
    }
}
