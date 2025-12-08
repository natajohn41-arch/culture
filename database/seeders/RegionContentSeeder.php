<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Contenu;
use App\Models\Media;
use App\Models\Commentaire;
use App\Models\Langue;
use App\Models\Utilisateur;

class RegionContentSeeder extends Seeder
{
    public function run(): void
    {
        $regions = Region::all();
        if ($regions->isEmpty()) return;

        $lang = Langue::first() ?: Langue::create(['nom_langue'=>'Français','code_langue'=>'fr','description'=>'Défaut']);
        $auteur = Utilisateur::whereHas('role', function($q){ $q->where('nom_role','Auteur'); })->first();
        if (! $auteur) {
            $auteur = Utilisateur::first();
            if (! $auteur) return;
        }

        $typeMedias = \App\Models\TypeMedia::all()->keyBy(function($t){ return strtolower($t->nom_media); });
        $typeContenus = \App\Models\TypeContenu::all();
        if ($typeContenus->isEmpty()) {
            $typeContenus = collect([\App\Models\TypeContenu::create(['nom_contenu' => 'Article'])]);
        }

        foreach ($regions as $region) {
            // if region already has some contents, skip creating to avoid duplicates
            if ($region->contenus_count > 0) continue;

            // create 2 contenus for this region (1 article, 1 média variation)
            $chosenTypes = $typeContenus->take(2);
            $i = 1;
            foreach ($chosenTypes as $type) {
                $titre = $region->nom_region . ' - ' . $type->nom_contenu . ' exemple ' . $i;
                $contenu = Contenu::create([
                    'titre' => $titre,
                    'texte' => "Contenu représentatif de la région {$region->nom_region} (type {$type->nom_contenu}).",
                    'date_creation' => now(),
                    'statut' => 'valide',
                    'id_region' => $region->id_region,
                    'id_langue' => $lang->id_langue,
                    'id_auteur' => $auteur->id_utilisateur,
                    'id_type_contenu' => $type->id_type_contenu ?? $type->id,
                ]);

                // add a simple media
                Media::create([
                    'chemin' => "seed/regions/{$region->id_region}/{$contenu->id_contenu}.jpg",
                    'description' => "Image pour {$titre}",
                    'id_contenu' => $contenu->id_contenu,
                    'id_type_media' => ($typeMedias->get('image')->id_type_media ?? 1),
                    'fichier' => "{$contenu->id_contenu}.jpg",
                ]);

                // sample comment
                Commentaire::create([
                    'texte' => "Exemple de commentaire pour {$titre}",
                    'note' => rand(3,5),
                    'date' => now(),
                    'id_utilisateur' => $auteur->id_utilisateur,
                    'id_contenu' => $contenu->id_contenu,
                ]);

                $i++;
            }
        }
    }
}
