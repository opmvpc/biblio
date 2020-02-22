<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $fillable = [
        'auteurs',
        'reference',
        'titre',
        'resume',
        'date',
        'url',
        'abstract',
        'doi',
        'bibtex',
        'keywords',
        'path_fiche_lecture',
        'path_article',
    ];

    protected $dates = [
        'date',
    ];

    public function cite()
    {
        return $this
            ->belongsToMany(Article::class, 'cite', 'cite_id', 'citeur_id')
            ->orderBy('date', 'Desc');
    }

    public function estCite()
    {
        return $this
            ->belongsToMany(Article::class, 'cite', 'citeur_id', 'cite_id')
            ->orderBy('date', 'Desc');
    }

    public function categories()
    {
        return $this->BelongsToMany(Categorie::class);
    }

    public function saveCategories(?array $categoriesIds): void
    {
        if ($categoriesIds != null) {
            $this->categories()->sync($categoriesIds);
        }
    }

    public function attachCite(array $citeIds): void
    {
        $this->cite()->attach($citeIds);
    }

    public function detachCite(int $citeId): void
    {
        $this->cite()->detach($citeId);
    }

    public function attachEstCite(array $citeIds): void
    {
        $this->estCite()->attach($citeIds);
    }

    public function detachEstCite(int $citeId): void
    {
        $this->estCite()->detach($citeId);
    }

    public function savePdfArticle(): string
    {
        $this->deletePdfArticle();

        $path = request()->file('file')->store('pdfs', 'public');
        $this->path_article = $path;
        $this->save();

        return $path;
    }

    public function deletePdfArticle()
    {
        if ($this->path_article) {
            Storage::disk('public')->delete($this->path_article);
        }
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'article_keyword', 'article_id', 'keyword_id');
    }

    public function attachKeyword(int $keywordId): void
    {
        $this->keywords()->attach($keywordId);
    }

    public function attachKeywords(array $keywordIds): void
    {
        $this->keywords()->attach($keywordIds);
    }

    public function detachKeyword(int $keywordId): void
    {
        $this->keywords()->detach($keywordId);
    }
}
