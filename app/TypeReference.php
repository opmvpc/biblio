<?php

namespace App;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

class TypeReference extends Model
{
    use Sluggable;

    protected $fillable = [
        'nom',
        'slug',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
