<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('type_medias')->insert([
            ['id_type_media' => 1, 'nom_media' => 'Image'],
            ['id_type_media' => 2, 'nom_media' => 'Vidéo'],
            ['id_type_media' => 3, 'nom_media' => 'Audio'],
            ['id_type_media' => 4, 'nom_media' => 'Document'],
        ]);
    }
}
