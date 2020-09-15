<?php

use App\Categorie;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Article::class, 20)
            ->create()
            ->each(function ($article) {
                $article->categories()->sync($this->getCategories());
            });
    }

    public function getCategories(): array
    {
        $categories = [];

        for ($i = 0; $i < rand(1, 8); $i++) {
            $randomCategorieId = rand(1, Categorie::count());
            if (! in_array($randomCategorieId, $categories)) {
                $categories[] = $randomCategorieId;
            }
        }

        return $categories;
    }
}
