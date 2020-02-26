<?php

namespace App;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use Sluggable;

    protected $fillable = [
        'reference',
        'titre',
        'slug',
        'resume',
        'date',
        'url',
        'abstract',
        'doi',
        'bibtex',
        'path_fiche_lecture',
        'path_article',
        'pertinence',
        'type_reference_id',
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


    public function type()
    {
        return $this->belongsTo(TypeReference::class, 'type_reference_id', 'id');
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
        $this->keywords()->syncWithoutDetaching([$keywordId]);
    }

    public function attachKeywords(array $keywordIds): void
    {
        $this->keywords()->syncWithoutDetaching($keywordIds);
    }

    public function detachKeyword(int $keywordId): void
    {
        $this->keywords()->detach($keywordId);
    }

    public function auteurs()
    {
        return $this->belongsToMany(Auteur::class, 'article_auteur', 'article_id', 'auteur_id');
    }

    public function attachAuteur(int $auteurId): void
    {
        $this->auteurs()->syncWithoutDetaching([$auteurId]);
    }

    public function attachAuteurs(array $auteurIds): void
    {
        $this->auteurs()->syncWithoutDetaching($auteurIds);
    }

    public function detachAuteur(int $auteurId): void
    {
        $this->auteurs()->detach($auteurId);
    }

    public static function getPertinenceDatas(): Collection
    {
        $datas = collect();
        $datas->put(1, collect(['nom' => 'Faible', 'couleur' => 'danger']));
        $datas->put(2, collect(['nom' => 'Moyenne', 'couleur' => 'info']));
        $datas->put(3, collect(['nom' => 'Haute', 'couleur' => 'success']));

        return $datas;
    }

    public function getPertinenceData(string $attributeName): string
    {
        if (! $this->pertinence) {
            return '';
        }

        return static::getPertinenceDatas()->get($this->pertinence)->get($attributeName);
    }
}
