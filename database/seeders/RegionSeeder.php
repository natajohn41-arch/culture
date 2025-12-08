<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des 12 départements (régions administratives) du Bénin
        $regions = [
            ['nom_region' => 'Alibori', 'description' => 'Département d\'Alibori', 'population' => 860000, 'superficie' => 25800, 'localisation' => 'Bénin - Nord'],
            ['nom_region' => 'Atakora', 'description' => 'Département d\'Atakora', 'population' => 650000, 'superficie' => 20000, 'localisation' => 'Bénin - Nord-Ouest'],
            ['nom_region' => 'Atlantique', 'description' => 'Département de l\'Atlantique', 'population' => 1200000, 'superficie' => 3800, 'localisation' => 'Bénin - Sud'],
            ['nom_region' => 'Borgou', 'description' => 'Département du Borgou', 'population' => 1000000, 'superficie' => 25000, 'localisation' => 'Bénin - Est'],
            ['nom_region' => 'Collines', 'description' => 'Département des Collines', 'population' => 700000, 'superficie' => 6500, 'localisation' => 'Bénin - Centre'],
            ['nom_region' => 'Donga', 'description' => 'Département de la Donga', 'population' => 550000, 'superficie' => 8000, 'localisation' => 'Bénin - Nord-Ouest'],
            ['nom_region' => 'Kouffo', 'description' => 'Département du Kouffo', 'population' => 540000, 'superficie' => 3000, 'localisation' => 'Bénin - Sud-Ouest'],
            ['nom_region' => 'Littoral', 'description' => 'Département du Littoral (Cotonou)', 'population' => 700000, 'superficie' => 75, 'localisation' => 'Bénin - Sud'],
            ['nom_region' => 'Mono', 'description' => 'Département du Mono', 'population' => 650000, 'superficie' => 1600, 'localisation' => 'Bénin - Sud'],
            ['nom_region' => 'Ouémé', 'description' => 'Département de l\'Ouémé', 'population' => 1200000, 'superficie' => 3400, 'localisation' => 'Bénin - Sud-Est'],
            ['nom_region' => 'Plateau', 'description' => 'Département du Plateau', 'population' => 720000, 'superficie' => 3000, 'localisation' => 'Bénin - Centre-Est'],
            ['nom_region' => 'Zou', 'description' => 'Département du Zou', 'population' => 900000, 'superficie' => 5600, 'localisation' => 'Bénin - Centre'],
        ];

        // Utiliser upsert pour éviter les doublons sur le champ `nom_region`.
        \Illuminate\Support\Facades\DB::table('regions')->upsert(
            $regions,
            ['nom_region'],
            ['description', 'population', 'superficie', 'localisation']
        );
    }
}
