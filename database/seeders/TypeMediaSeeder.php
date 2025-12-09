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
        $typeMedias = [
            ['id_type_media' => 1, 'nom_media' => 'Image'],
            ['id_type_media' => 2, 'nom_media' => 'Vidéo'],
            ['id_type_media' => 3, 'nom_media' => 'Audio'],
            ['id_type_media' => 4, 'nom_media' => 'Document'],
        ];

        foreach ($typeMedias as $typeMedia) {
            \Illuminate\Support\Facades\DB::table('type_medias')
                ->updateOrInsert(
                    ['id_type_media' => $typeMedia['id_type_media']],
                    $typeMedia
                );
        }
    }
}
