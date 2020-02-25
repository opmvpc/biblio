<?php

namespace App\Services;

use App\Article;
use App\Auteur;
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
        $this->bibtex = preg_replace('/\@(\w*)\{((\d|\.|\/)*|http.*),/', '@$1{', $this->bibtex);
        // dd($this->bibtex);
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
                'titre' => Str::limit($this->sanatizeText($article->get('title'), 295)),
                'auteurs' => $article->get('author'),
                'reference' => $this->sanatizeText($article->get('citation-key')),
                'date' => $this->getDate(intval($article->get('year')), intval($article->get('month'))),
                'url' => $this->sanatizeText($article->get('url')),
                'bibtex' => $article->get('"_original'),
                'abstract' => $article->get('abstract'),
                'keywords' => $article->get('keywords'),
                'doi' => $this->sanatizeText($article->get('doi')),
            ];
        })
        ->each( function ($article) {
            $nouvelArticle = Article::firstOrCreate(['titre' => $article['titre']], $article);

            if (request()->has('cite_par')) {
                $nouvelArticle->attachEstCite(request()->cite_par);
            } if (request()->has('cite')) {
                $nouvelArticle->attachCite(request()->cite);
            }

            $this->attachResources($nouvelArticle, $article['keywords'], 'Keyword');
            $this->attachResources($nouvelArticle, $article['auteurs'], 'Auteur');
        });
    }

    public function bibtex(): string
    {
        return $this->bibtex;
    }

    private function sanatizeText($bibtexAttribute): string
    {
        $sanatyzedText = preg_replace('/{|}/', '', $bibtexAttribute);
        return $sanatyzedText;
    }

    private function getDate(?int $annee, ?int $mois): Carbon
    {
        return Carbon::createFromDate($annee ?? 1975, $mois ?? 1, 1);
    }

    public function attachResources(Article $article, ?string $attachables, string $attachableType): void
    {
        collect(explode(',', $attachables))
            ->map( function ($attachable) {
                return trim($attachable);
            })
            ->filter()
            ->each( function ($attachable) use ($article, $attachableType) {
                $attachable = $this->sanatizeText($attachable);
                if ($attachableType == 'Keyword') {
                    $this->attachKeyword($article, $attachable);
                } elseif ($attachableType == 'Auteur') {
                    $this->attachAuteur($article, $attachable);
                }
            })
            ;
    }

    public function attachKeyword(Article $article, string $keyword): void
    {
        $nouveauKeyword = Keyword::firstOrCreate(['nom' => Str::limit($keyword, 295)]);
        $article->attachKeyword($nouveauKeyword->id);
    }

    public function attachAuteur(Article $article, string $auteur): void
    {
        $nouveauKeyword = Auteur::firstOrCreate(['nom' => Str::limit($auteur, 295)]);
        $article->attachAuteur($nouveauKeyword->id);
    }
}
