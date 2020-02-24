<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Set le slug quand le nom est modifié
 */
trait Sluggable
{
    public function setNomAttribute($value): void
    {
        $this->attributes['nom'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setTitreAttribute($value): void
    {
        $this->attributes['titre'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
