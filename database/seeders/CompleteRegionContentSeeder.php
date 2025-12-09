<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeContenu;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Utilisateur;
use Carbon\Carbon;

class CompleteRegionContentSeeder extends Seeder
{
    /**
     * Seed des contenus de tous les types dans toutes les régions
     */
    public function run(): void
    {
        // Récupérer les données nécessaires
        $regions = Region::all();
        $langues = Langue::all();
        $typesContenus = TypeContenu::all();
        
        // Trouver un auteur - méthode plus robuste avec join
        $roleAuteur = \App\Models\Role::where('nom_role', 'Auteur')->first();
        $auteurs = collect();
        
        if ($roleAuteur) {
            $auteurs = Utilisateur::where('id_role', $roleAuteur->id)->get();
        }
        
        if ($auteurs->isEmpty()) {
            // Si pas d'auteur, utiliser le premier utilisateur admin
            $roleAdmin = \App\Models\Role::where('nom_role', 'Admin')->first();
            if ($roleAdmin) {
                $auteurs = Utilisateur::where('id_role', $roleAdmin->id)->take(1)->get();
            }
        }
        
        if ($auteurs->isEmpty()) {
            // Dernière tentative : utiliser n'importe quel utilisateur actif
            $auteurs = Utilisateur::where('statut', 'actif')->take(1)->get();
        }
        
        if ($auteurs->isEmpty()) {
            $this->command->error('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs avec UsersPerRoleSeeder.');
            return;
        }
        
        $auteur = $auteurs->first();
        $langue = $langues->first() ?? Langue::first();
        
        if ($regions->isEmpty() || $typesContenus->isEmpty()) {
            $this->command->error('Régions ou types de contenus manquants. Vérifiez RegionSeeder et TypeContenuSeeder.');
            return;
        }
        
        if (!$langue) {
            $this->command->error('Langue manquante. Vérifiez LangueSeeder.');
            return;
        }
        
        // Templates de contenus par type
        $templates = [
            'Article' => [
                'titre' => 'Article Culturel de {region}',
                'texte' => '<h2>Introduction</h2><p>Découvrez la richesse culturelle de la région de {region}. Cette région possède un patrimoine exceptionnel qui mérite d\'être exploré.</p><h2>Histoire et Traditions</h2><p>Les traditions de {region} remontent à plusieurs siècles et continuent d\'influencer la vie quotidienne de ses habitants.</p><h2>Conclusion</h2><p>La région de {region} est un véritable trésor culturel à découvrir.</p>',
                'premium' => false
            ],
            'Histoire / Légende' => [
                'titre' => 'Légende Traditionnelle de {region}',
                'texte' => '<h2>La Légende</h2><p>Il était une fois, dans la région de {region}, une légende qui se transmettait de génération en génération...</p><p>Cette histoire raconte comment les ancêtres ont fondé cette terre et ont établi les premières traditions qui régissent encore aujourd\'hui la vie des habitants de {region}.</p>',
                'premium' => true,
                'prix' => 2500
            ],
            'Conte / Fable' => [
                'titre' => 'Conte Populaire de {region}',
                'texte' => '<h2>Le Conte</h2><p>Dans la région de {region}, on raconte cette histoire aux enfants pour leur enseigner les valeurs importantes de la vie...</p><p>La morale de ce conte nous rappelle l\'importance de la sagesse et du respect des traditions.</p>',
                'premium' => false
            ],
            'Proverbe / Sagesse' => [
                'titre' => 'Proverbes et Sagesses de {region}',
                'texte' => '<h2>Collection de Proverbes</h2><ul><li><strong>"Proverbe 1"</strong> - Signification et enseignement</li><li><strong>"Proverbe 2"</strong> - Sagesse ancestrale</li><li><strong>"Proverbe 3"</strong> - Leçon de vie</li></ul><h2>L\'Importance</h2><p>Ces proverbes transmettent la sagesse des ancêtres de {region}.</p>',
                'premium' => true,
                'prix' => 1500
            ],
            'Chanson / Musique' => [
                'titre' => 'Musique Traditionnelle de {region}',
                'texte' => '<h2>Introduction</h2><p>La musique traditionnelle de {region} est caractérisée par ses rythmes uniques et ses instruments spécifiques.</p><h2>Instruments</h2><p>Les instruments traditionnels de cette région incluent...</p>',
                'premium' => true,
                'prix' => 3000
            ],
            'Danse traditionnelle' => [
                'titre' => 'Danses Traditionnelles de {region}',
                'texte' => '<h2>Les Danses</h2><p>Les danses traditionnelles de {region} sont un spectacle à voir absolument. Chaque mouvement a une signification profonde liée à l\'histoire et à la culture locale.</p>',
                'premium' => false
            ],
            'Recette culinaire' => [
                'titre' => 'Spécialité Culinaire de {region}',
                'texte' => '<h2>La Recette</h2><p>Découvrez la recette traditionnelle de {region}, un plat qui ravit les papilles depuis des générations.</p><h2>Ingrédients</h2><ul><li>Ingrédient 1</li><li>Ingrédient 2</li><li>Ingrédient 3</li></ul><h2>Préparation</h2><p>Étapes de préparation de ce délicieux plat...</p>',
                'premium' => true,
                'prix' => 2000
            ],
            'Artisanat' => [
                'titre' => 'Artisanat Traditionnel de {region}',
                'texte' => '<h2>L\'Artisanat</h2><p>L\'artisanat de {region} est reconnu pour sa qualité et son authenticité. Les artisans transmettent leurs savoir-faire de génération en génération.</p>',
                'premium' => true,
                'prix' => 4000
            ],
            'Cérémonie / Rituel' => [
                'titre' => 'Cérémonies et Rituels de {region}',
                'texte' => '<h2>Les Cérémonies</h2><p>Les cérémonies traditionnelles de {region} sont des moments importants de la vie communautaire. Elles rythment l\'année et renforcent les liens sociaux.</p>',
                'premium' => false
            ],
            'Personnage historique' => [
                'titre' => 'Personnage Historique de {region}',
                'texte' => '<h2>Biographie</h2><p>Ce personnage historique de {region} a marqué l\'histoire de la région par ses actions et sa vision.</p><h2>Héritage</h2><p>Son héritage continue d\'influencer la région aujourd\'hui.</p>',
                'premium' => true,
                'prix' => 3500
            ],
            'Lieu culturel' => [
                'titre' => 'Site Culturel de {region}',
                'texte' => '<h2>Le Site</h2><p>Ce site culturel de {region} est un lieu emblématique qui témoigne de la richesse historique et culturelle de la région.</p><h2>Visite</h2><p>Ce lieu mérite une visite pour comprendre l\'histoire de {region}.</p>',
                'premium' => false
            ],
            'Poème' => [
                'titre' => 'Poème sur {region}',
                'texte' => '<div style="font-style: italic; line-height: 2;"><p><strong>Ô {region}, terre de mes ancêtres,</strong></p><p>Où la culture fleurit comme les fleurs,</p><p>Où les traditions vivent éternellement,</p><p>Où l\'histoire se raconte chaque jour.</p></div>',
                'premium' => true,
                'prix' => 1000
            ],
            'Vidéo' => [
                'titre' => 'Documentaire sur {region}',
                'texte' => '<h2>Documentaire</h2><p>Ce documentaire explore la culture, l\'histoire et les traditions de la région de {region}.</p><p><em>Note : Ce contenu inclurait une vidéo documentaire complète une fois les médias uploadés.</em></p>',
                'premium' => true,
                'prix' => 5000
            ],
            'Galerie photo' => [
                'titre' => 'Galerie Photo de {region}',
                'texte' => '<h2>Galerie</h2><p>Cette galerie présente les plus beaux moments et lieux de la région de {region}.</p><p><em>Note : Cette galerie inclurait des photos haute résolution une fois les médias uploadés.</em></p>',
                'premium' => false
            ],
            'Document' => [
                'titre' => 'Document Historique de {region}',
                'texte' => '<h2>Document</h2><p>Ce document historique retrace l\'histoire de la région de {region} à travers les archives et les témoignages.</p><p><em>Note : Ce contenu inclurait le document PDF complet une fois les médias uploadés.</em></p>',
                'premium' => true,
                'prix' => 2500
            ],
        ];
        
        $created = 0;
        $skipped = 0;
        
        // Pour chaque région
        foreach ($regions as $region) {
            // Pour chaque type de contenu
            foreach ($typesContenus as $typeContenu) {
                // Déterminer les IDs corrects
                $regionId = $region->id_region ?? $region->id ?? 1;
                $typeContenuId = $typeContenu->id_type_contenu ?? $typeContenu->id ?? 1;
                
                // Vérifier si un contenu de ce type existe déjà pour cette région
                $exists = Contenu::where('id_region', $regionId)
                    ->where('id_type_contenu', $typeContenuId)
                    ->exists();
                
                if ($exists) {
                    $skipped++;
                    continue;
                }
                
                // Récupérer le template
                $template = $templates[$typeContenu->nom_contenu] ?? null;
                
                if (!$template) {
                    // Template par défaut si le type n'est pas dans la liste
                    $template = [
                        'titre' => $typeContenu->nom_contenu . ' de {region}',
                        'texte' => '<h2>Contenu</h2><p>Découvrez ce contenu de type "' . $typeContenu->nom_contenu . '" de la région de {region}.</p>',
                        'premium' => false
                    ];
                }
                
                // Remplacer {region} par le nom de la région
                $titre = str_replace('{region}', $region->nom_region, $template['titre']);
                $texte = str_replace('{region}', $region->nom_region, $template['texte']);
                
                // Vérifier si ce titre existe déjà
                if (Contenu::where('titre', $titre)->exists()) {
                    $skipped++;
                    continue;
                }
                
                // Déterminer les IDs corrects selon la structure de la table
                $regionId = $region->id_region ?? $region->id ?? 1;
                $langueId = $langue->id_langue ?? $langue->id ?? 1;
                $typeContenuId = $typeContenu->id_type_contenu ?? $typeContenu->id ?? 1;
                $auteurId = $auteur->id_utilisateur ?? $auteur->id ?? 1;
                
                // Créer le contenu
                Contenu::create([
                    'titre' => $titre,
                    'texte' => $texte,
                    'id_region' => $regionId,
                    'id_langue' => $langueId,
                    'id_type_contenu' => $typeContenuId,
                    'id_auteur' => $auteurId,
                    'statut' => 'valide',
                    'date_creation' => Carbon::now()->subDays(rand(1, 60)),
                    'date_validation' => Carbon::now()->subDays(rand(1, 60)),
                    'id_moderateur' => $auteurId,
                    'est_premium' => $template['premium'] ?? false,
                    'prix' => $template['prix'] ?? null
                ]);
                
                $created++;
            }
        }
        
        $this->command->info("✅ {$created} nouveaux contenus créés !");
        $this->command->info("⏭️  {$skipped} contenus déjà existants (ignorés)");
        
        // Statistiques finales
        $totalParRegion = [];
        foreach ($regions as $region) {
            $regionId = $region->id_region ?? $region->id ?? 1;
            $count = Contenu::where('id_region', $regionId)->count();
            $totalParRegion[] = "{$region->nom_region}: {$count} contenus";
        }
        
        $this->command->info("📊 Répartition par région:");
        foreach ($totalParRegion as $stat) {
            $this->command->info("   - {$stat}");
        }
    }
}

