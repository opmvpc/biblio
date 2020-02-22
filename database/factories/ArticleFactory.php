<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'auteurs' => getAuteurs($faker),
        'titre' => $faker->catchPhrase,
        'reference' => $faker->year().'_'.$faker->lastName,
        'resume' => $faker->sentences($nb = 3, $asText = true),
        'date' => $faker->dateTimeBetween($startDate = '-40 years', $endDate = 'now', $timezone = null),
        'url' => $faker->url,
        'abstract' => $faker->sentences($nb = 8, $asText = true),
        'doi' => $faker->isbn13,
        'keywords' => $faker->words($nb = 3, $asText = true),
        'path_fiche_lecture' => 'test.pdf',
        'path_article' => 'test.pdf',
    ];
});


function getAuteurs($faker)
{
    $auteurs = collect();
    for ($i=0; $i < rand(1, 5); $i++) {
        $auteurs->push(['nom' => $faker->name]);
    }
    return $auteurs->implode('nom', ',');
}
