<?php

namespace Database\Seeders;

use App\Models\Langue;
use Illuminate\Database\Seeder;

class LangueSeeder extends Seeder
{
    public function run()
    {
        $langues = [
            [
                'nom_langue' => 'Français',
                'code_langue' => 'fr',
                'description' => 'Langue officielle du Bénin'
            ],
            [
                'nom_langue' => 'Fon',
                'code_langue' => 'fon',
                'description' => 'Langue locale parlée dans le sud du Bénin'
            ],
            [
                'nom_langue' => 'Yoruba',
                'code_langue' => 'yor',
                'description' => 'Langue locale parlée dans le sud-est du Bénin'
            ],
            [
                'nom_langue' => 'Bariba',
                'code_langue' => 'bba',
                'description' => 'Langue locale parlée dans le nord du Bénin'
            ],
            [
                'nom_langue' => 'Dendi',
                'code_langue' => 'ddn',
                'description' => 'Langue locale parlée dans le nord du Bénin'
            ],
        ];

        foreach ($langues as $langue) {
            Langue::firstOrCreate(['code_langue' => $langue['code_langue']], $langue);
        }
    }
}