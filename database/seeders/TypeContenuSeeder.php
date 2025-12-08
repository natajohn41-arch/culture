<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeContenu;

class TypeContenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ne pas vider si des contenus existent déjà (à cause des foreign keys)
        // TypeContenu::truncate();

        // Types de contenus culturels pour le site Culture Bénin
        $typesContenus = [
            [
                'nom_contenu' => 'Article',
                'description' => 'Articles culturels, historiques ou informatifs sur le Bénin'
            ],
            [
                'nom_contenu' => 'Histoire / Légende',
                'description' => 'Histoires et légendes traditionnelles du Bénin'
            ],
            [
                'nom_contenu' => 'Conte / Fable',
                'description' => 'Contes et fables populaires béninoises transmis oralement'
            ],
            [
                'nom_contenu' => 'Proverbe / Sagesse',
                'description' => 'Proverbes et sagesses traditionnelles béninoises'
            ],
            [
                'nom_contenu' => 'Chanson / Musique',
                'description' => 'Chansons et musiques traditionnelles béninoises'
            ],
            [
                'nom_contenu' => 'Danse traditionnelle',
                'description' => 'Danses traditionnelles et chorégraphies culturelles'
            ],
            [
                'nom_contenu' => 'Recette culinaire',
                'description' => 'Recettes de plats traditionnels béninois'
            ],
            [
                'nom_contenu' => 'Artisanat',
                'description' => 'Techniques et œuvres artisanales béninoises'
            ],
            [
                'nom_contenu' => 'Cérémonie / Rituel',
                'description' => 'Cérémonies et rituels traditionnels béninois'
            ],
            [
                'nom_contenu' => 'Personnage historique',
                'description' => 'Biographies de personnages historiques béninois'
            ],
            [
                'nom_contenu' => 'Lieu culturel',
                'description' => 'Sites historiques et lieux culturels du Bénin'
            ],
            [
                'nom_contenu' => 'Poème',
                'description' => 'Poèmes et œuvres poétiques béninoises'
            ],
            [
                'nom_contenu' => 'Vidéo',
                'description' => 'Vidéos documentaires et culturelles'
            ],
            [
                'nom_contenu' => 'Galerie photo',
                'description' => 'Galerie de photos culturelles et artistiques'
            ],
            [
                'nom_contenu' => 'Document',
                'description' => 'Documents historiques, archives et textes officiels'
            ],
        ];

        foreach ($typesContenus as $type) {
            TypeContenu::create($type);
        }

        $this->command->info('Types de contenus créés avec succès !');
    }
}
