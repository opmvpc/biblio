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
            'nom' => 'Pédagogie',
        ]);

        Categorie::create([
            'nom' => 'IDE',
        ]);

        Categorie::create([
            'nom' => 'Modularité',
        ]);

        Categorie::create([
            'nom' => 'Représentation graphique',
        ]);

        Categorie::create([
            'nom' => 'Paradigme',
        ]);
    }
}
