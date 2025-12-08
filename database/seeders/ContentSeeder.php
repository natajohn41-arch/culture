<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contenu;
use App\Models\Media;
use App\Models\Commentaire;
use App\Models\Utilisateur;
use App\Models\Langue;
use App\Models\Region;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $lang = Langue::first() ?: Langue::create(['nom_langue'=>'Français','code_langue'=>'fr','description'=>'Défaut']);
        $region = Region::first() ?: Region::create(['nom_region'=>'Non défini']);

        // Auteur de référence (préférer un utilisateur avec le rôle 'Auteur')
        $auteur = Utilisateur::whereHas('role', function($q){ $q->where('nom_role','Auteur'); })->first();
        if (! $auteur) {
            $auteur = Utilisateur::first();
            if (! $auteur) return; // pas d'utilisateur
        }

        $typeMedias = \App\Models\TypeMedia::all()->keyBy(function($t){ return strtolower($t->nom_media); });
        $typeContenus = \App\Models\TypeContenu::all();

        if ($typeContenus->isEmpty()) {
            // fallback si aucun type n'existe
            $typeContenus = collect([
                \App\Models\TypeContenu::create(['nom_contenu' => 'Article']),
            ]);
        }

        foreach ($typeContenus as $type) {
            // créer 2 contenus publiés par type
            for ($i = 1; $i <= 2; $i++) {
                $titre = $type->nom_contenu . ' exemple ' . $i;
                $texte = "Contenu de démonstration pour {$type->nom_contenu} ($i).\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.";

                $contenu = Contenu::create([
                    'titre' => $titre,
                    'texte' => $texte,
                    'date_creation' => now()->subMinutes(rand(0, 1000)),
                    'statut' => 'valide', // publié
                    'id_region' => $region->id_region ?? null,
                    'id_langue' => $lang->id_langue,
                    'id_auteur' => $auteur->id_utilisateur,
                    'id_type_contenu' => $type->id_type_contenu ?? $type->id,
                ]);

                // Ajouter des médias selon le type de contenu
                $nomType = strtolower($type->nom_contenu);
                if (str_contains($nomType, 'article') || str_contains($nomType, 'galerie')) {
                    // image(s)
                    $count = str_contains($nomType, 'galerie') ? 3 : 1;
                    for ($m = 1; $m <= $count; $m++) {
                        Media::create([
                            'chemin' => "seed/{$type->nom_contenu}/{$contenu->id_contenu}_{$m}.jpg",
                            'description' => "Image {$m} pour {$titre}",
                            'id_contenu' => $contenu->id_contenu,
                            'id_type_media' => ($typeMedias->get('image')->id_type_media ?? 1),
                            'fichier' => "{$contenu->id_contenu}_{$m}.jpg",
                        ]);
                    }
                } elseif (str_contains($nomType, 'vid')) {
                    // vidéo
                    Media::create([
                        'chemin' => "seed/{$type->nom_contenu}/{$contenu->id_contenu}.mp4",
                        'description' => "Vidéo pour {$titre}",
                        'id_contenu' => $contenu->id_contenu,
                        'id_type_media' => ($typeMedias->get('vidéo') ? $typeMedias->get('vidéo')->id_type_media : ($typeMedias->get('video')->id_type_media ?? 2)),
                        'fichier' => "{$contenu->id_contenu}.mp4",
                    ]);
                } elseif (str_contains($nomType, 'audio')) {
                    Media::create([
                        'chemin' => "seed/{$type->nom_contenu}/{$contenu->id_contenu}.mp3",
                        'description' => "Audio pour {$titre}",
                        'id_contenu' => $contenu->id_contenu,
                        'id_type_media' => ($typeMedias->get('audio')->id_type_media ?? 3),
                        'fichier' => "{$contenu->id_contenu}.mp3",
                    ]);
                } else {
                    // document ou autre
                    Media::create([
                        'chemin' => "seed/{$type->nom_contenu}/{$contenu->id_contenu}.pdf",
                        'description' => "Document pour {$titre}",
                        'id_contenu' => $contenu->id_contenu,
                        'id_type_media' => ($typeMedias->get('document')->id_type_media ?? 4),
                        'fichier' => "{$contenu->id_contenu}.pdf",
                    ]);
                }

                // Un commentaire d'exemple
                Commentaire::create([
                    'texte' => "Commentaire de démonstration pour {$titre}.",
                    'note' => rand(3,5),
                    'date' => now(),
                    'id_utilisateur' => $auteur->id_utilisateur,
                    'id_contenu' => $contenu->id_contenu,
                ]);
            }
        }
    }
}
