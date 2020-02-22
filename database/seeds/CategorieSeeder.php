<?php

use App\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categorie::create([
            'nom' => 'Learning/Teaching',
        ]);

        Categorie::create([
            'nom' => 'IDE',
        ]);

        Categorie::create([
            'nom' => 'Meta Modeling',
        ]);

        Categorie::create([
            'nom' => 'Turtle Graphics',
        ]);
    }
}
