<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeContenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('type_contenus')->insert([
            ['id_type_contenu' => 1, 'nom_contenu' => 'Article'],
            ['id_type_contenu' => 2, 'nom_contenu' => 'Vidéo'],
            ['id_type_contenu' => 3, 'nom_contenu' => 'Galerie'],
            ['id_type_contenu' => 4, 'nom_contenu' => 'Document'],
        ]);
    }
}
