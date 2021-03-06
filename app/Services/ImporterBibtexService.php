<?php

namespace App\Services;

use App\Article;
use App\Auteur;
use App\Keyword;
use App\TypeReference;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Xianzhe18\BibTexParser\Listener;
use Xianzhe18\BibTexParser\Parser;

class ImporterBibtexService
{
    private $bibtex;
    private $doi;
    private $ref;

    public function __construct(string $bibtex, ?string $doi = null)
    {
        $this->bibtex = $bibtex;
        $this->doi = $doi;
    }

    public function save(): void
    {
        $articles = $this->parse();
        $this->createArticles($articles);
    }

    private function parse(): array
    {
        $regex = '/\@(\w*)\{(.*|http.*),/';

        $this->extractRef();

        // suppression de la référence du bibtex, car incompatible avec le parser
        $this->bibtex = preg_replace($regex, '@$1{', $this->bibtex);
        $parser = new Parser();
        $listener = new Listener();
        $parser->addListener($listener);
        $parser->parseString($this->bibtex);
        $articles = $listener->export();

        return $articles;
    }

    // récupération du doi ou de la référence depuis le bibtex
    private function extractRef(): void
    {
        $result = [];
        $ref = '';

        preg_match('/\@\w*\{(.*)/', $this->bibtex(), $result);
        $ref = explode(',', $result[1])[0];

        if (Str::startsWith($ref, '10.')) {
            if ($this->doi === null) {
                $this->doi = $ref;
            }
        } else {
            $this->ref = $ref;
        }
    }

    private function createArticles(array $articles): void
    {
        collect($articles)
        ->map(function ($article) {
            return collect($article);
        })
        ->map(function ($article) {
            return [
                'titre' => Str::limit($this->sanatizeText($article->get('title'), 295)),
                'auteurs' => $article->get('author'),
                'reference' => $this->sanatizeText($article->get('citation-key')) === '' ? $this->ref : $this->sanatizeText($article->get('citation-key')),
                'date' => $this->getDate(intval($article->get('year')), intval($article->get('month'))),
                'url' => $this->sanatizeText($article->get('url')),
                'bibtex' => $article->get('_original'),
                'abstract' => $article->get('abstract'),
                'keywords' => $article->get('keywords'),
                'doi' => $this->sanatizeText($article->get('doi')) === '' ? $this->doi : $this->sanatizeText($article->get('doi')),
                'type' => $article->get('type'),
            ];
        })
        ->each(function (array $article) {
            $nouvelArticle = Article::firstOrCreate(['slug' => Str::slug($article['titre'])], $article);

            if (is_object(request()) && request()->has('cite_par')) {
                $nouvelArticle->attachEstCite(request()->input('cite_par'));
            }
            if (is_object(request()) && request()->has('cite')) {
                $nouvelArticle->attachCite(request()->cite);
            }

            $nouveauType = TypeReference::firstOrCreate(['nom' => $article['type']]);
            $nouveauType->articles()->save($nouvelArticle);

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
        if (! $annee || $annee == -1) {
            $annee = 1975;
        }

        return Carbon::createFromDate($annee, $mois ?? 1, 1);
    }

    private function attachResources(Article $article, ?string $attachables, string $attachableType): void
    {
        collect(explode(',', $attachables ?? ''))
            ->map(function ($attachable) {
                return trim($attachable);
            })
            ->filter()
            ->each(function ($attachable) use ($article, $attachableType) {
                $attachable = $this->sanatizeText($attachable);
                if ($attachableType == 'Keyword') {
                    $this->attachKeyword($article, $attachable);
                } elseif ($attachableType == 'Auteur') {
                    $this->attachAuteur($article, $attachable);
                }
            })
            ;
    }

    private function attachKeyword(Article $article, string $keyword): void
    {
        $keyword = Str::limit($keyword, 295);
        $nouveauKeyword = Keyword::firstOrCreate(['slug' => Str::slug($keyword)], ['nom' => $keyword]);
        $article->attachKeyword($nouveauKeyword->id);
    }

    private function attachAuteur(Article $article, string $auteur): void
    {
        $auteur = $this->convertBadChars($auteur);
        $auteur = Str::limit($auteur, 295);
        $auteur = mb_convert_encoding($auteur, 'utf-8');
        $auteur = Auteur::firstOrCreate(['slug' => Str::slug($auteur)], ['nom' => $auteur]);
        $article->attachAuteur($auteur->id);
    }
    private function convertBadChars(string $auteur)
    {
        $auteur = preg_replace('/\\\'a/', 'á', $auteur);
        $auteur = preg_replace('/\\á/', 'á', $auteur);
        $auteur = preg_replace('/(\\\'i|\\\'\\\i)/', 'í', $auteur);
        $auteur = preg_replace('/(\\í|\\\\\í)/', 'í', $auteur);
        $auteur = preg_replace('/\^i/', 'i', $auteur);
        $auteur = preg_replace('/\\\'e/', 'é', $auteur);
        $auteur = preg_replace('/\\\´e/', 'é', $auteur);
        $auteur = preg_replace('/\\\`e/', 'è', $auteur);
        $auteur = preg_replace('/\\é/', 'é', $auteur);
        $auteur = preg_replace('/\\è/', 'è', $auteur);
        $auteur = preg_replace('/\\v/', '', $auteur);
        $auteur = preg_replace('/\\\^/', '', $auteur);
        $auteur = preg_replace('/\\"/', '', $auteur);
        $auteur = str_replace('\\', '', $auteur);

        return $auteur;
    }
}
