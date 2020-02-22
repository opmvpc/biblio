<?php

namespace App;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use Sluggable;

    protected $fillable = [
        'nom',
        'slug',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_keyword', 'keyword_id', 'article_id');
    }
}
