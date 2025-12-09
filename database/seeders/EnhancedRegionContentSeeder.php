<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeContenu;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Utilisateur;
use Carbon\Carbon;

class EnhancedRegionContentSeeder extends Seeder
{
    /**
     * Seed des contenus enrichis et variés pour chaque région
     */
    public function run(): void
    {
        // Récupérer les données nécessaires
        $regions = Region::all();
        $langues = Langue::all();
        $typesContenus = TypeContenu::all();
        
        // Trouver un auteur
        $roleAuteur = \App\Models\Role::where('nom_role', 'Auteur')->first();
        $auteurs = collect();
        
        if ($roleAuteur) {
            $auteurs = Utilisateur::where('id_role', $roleAuteur->id)->get();
        }
        
        if ($auteurs->isEmpty()) {
            $roleAdmin = \App\Models\Role::where('nom_role', 'Admin')->first();
            if ($roleAdmin) {
                $auteurs = Utilisateur::where('id_role', $roleAdmin->id)->take(1)->get();
            }
        }
        
        if ($auteurs->isEmpty()) {
            $auteurs = Utilisateur::where('statut', 'actif')->take(1)->get();
        }
        
        if ($auteurs->isEmpty()) {
            $this->command->error('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }
        
        $auteur = $auteurs->first();
        $langue = $langues->first() ?? Langue::first();
        
        if ($regions->isEmpty() || $typesContenus->isEmpty()) {
            $this->command->error('Régions ou types de contenus manquants.');
            return;
        }
        
        if (!$langue) {
            $this->command->error('Langue manquante.');
            return;
        }
        
        // Contenus spécifiques par région et par type
        $contenusParRegion = $this->getContenusParRegion();
        
        $created = 0;
        $skipped = 0;
        
        // Pour chaque région
        foreach ($regions as $region) {
            $regionId = $region->id_region ?? $region->id ?? 1;
            $regionName = $region->nom_region;
            
            // Pour chaque type de contenu
            foreach ($typesContenus as $typeContenu) {
                $typeContenuId = $typeContenu->id_type_contenu ?? $typeContenu->id ?? 1;
                $typeName = $typeContenu->nom_contenu;
                
                // Vérifier si un contenu de ce type existe déjà pour cette région
                $exists = Contenu::where('id_region', $regionId)
                    ->where('id_type_contenu', $typeContenuId)
                    ->exists();
                
                if ($exists) {
                    $skipped++;
                    continue;
                }
                
                // Récupérer le template de contenu pour cette région et ce type
                $contenuData = $this->getContenuData($regionName, $typeName, $contenusParRegion);
                
                // Vérifier si ce titre existe déjà
                if (Contenu::where('titre', $contenuData['titre'])->exists()) {
                    $skipped++;
                    continue;
                }
                
                // Déterminer les IDs corrects
                $langueId = $langue->id_langue ?? $langue->id ?? 1;
                $auteurId = $auteur->id_utilisateur ?? $auteur->id ?? 1;
                
                // Créer le contenu
                try {
                    Contenu::create([
                        'titre' => $contenuData['titre'],
                        'texte' => $contenuData['texte'],
                        'id_region' => $regionId,
                        'id_langue' => $langueId,
                        'id_type_contenu' => $typeContenuId,
                        'id_auteur' => $auteurId,
                        'statut' => 'valide',
                        'date_creation' => Carbon::now()->subDays(rand(1, 90)),
                        'date_validation' => Carbon::now()->subDays(rand(1, 90)),
                        'id_moderateur' => $auteurId,
                        'est_premium' => $contenuData['premium'] ?? false,
                        'prix' => $contenuData['prix'] ?? null
                    ]);
                    $created++;
                } catch (\Exception $e) {
                    $this->command->error("Erreur pour {$regionName} - {$typeName}: " . $e->getMessage());
                    continue;
                }
            }
        }
        
        $this->command->info("✅ {$created} nouveaux contenus créés !");
        $this->command->info("⏭️  {$skipped} contenus déjà existants (ignorés)");
        
        // Statistiques finales
        $this->command->info("\n📊 Répartition par région:");
        foreach ($regions as $region) {
            $regionId = $region->id_region ?? $region->id ?? 1;
            $count = Contenu::where('id_region', $regionId)->count();
            $premium = Contenu::where('id_region', $regionId)->where('est_premium', true)->count();
            $gratuit = Contenu::where('id_region', $regionId)->where('est_premium', false)->count();
            $this->command->info("   - {$region->nom_region}: {$count} contenus ({$premium} premium, {$gratuit} gratuits)");
        }
    }
    
    /**
     * Récupère les données de contenu pour une région et un type donnés
     */
    private function getContenuData($regionName, $typeName, $contenusParRegion)
    {
        // Si on a un contenu spécifique pour cette région et ce type
        if (isset($contenusParRegion[$regionName][$typeName])) {
            return $contenusParRegion[$regionName][$typeName];
        }
        
        // Sinon, utiliser un template générique
        return $this->getTemplateGenerique($regionName, $typeName);
    }
    
    /**
     * Retourne les contenus spécifiques par région
     */
    private function getContenusParRegion()
    {
        return [
            'Alibori' => $this->getContenusAlibori(),
            'Atakora' => $this->getContenusAtakora(),
            'Atlantique' => $this->getContenusAtlantique(),
            'Borgou' => $this->getContenusBorgou(),
            'Collines' => $this->getContenusCollines(),
            'Donga' => $this->getContenusDonga(),
            'Kouffo' => $this->getContenusKouffo(),
            'Littoral' => $this->getContenusLittoral(),
            'Mono' => $this->getContenusMono(),
            'Ouémé' => $this->getContenusOueme(),
            'Plateau' => $this->getContenusPlateau(),
            'Zou' => $this->getContenusZou(),
            'Nord' => $this->getContenusNord(),
        ];
    }
    
    /**
     * Template générique pour un type de contenu
     */
    private function getTemplateGenerique($regionName, $typeName)
    {
        $templates = [
            'Article' => [
                'titre' => "Patrimoine Culturel de {$regionName}",
                'texte' => "<h2>Introduction</h2><p>Découvrez la richesse culturelle exceptionnelle de la région de {$regionName}. Cette région possède un patrimoine unique qui mérite d'être exploré et préservé.</p><h2>Histoire et Traditions</h2><p>Les traditions de {$regionName} remontent à plusieurs siècles et continuent d'influencer la vie quotidienne de ses habitants. Les coutumes ancestrales sont transmises de génération en génération.</p><h2>Conclusion</h2><p>La région de {$regionName} est un véritable trésor culturel à découvrir absolument.</p>",
                'premium' => false
            ],
            'Histoire / Légende' => [
                'titre' => "Légende Ancestrale de {$regionName}",
                'texte' => "<h2>La Légende</h2><p>Il était une fois, dans la région de {$regionName}, une légende qui se transmettait de génération en génération...</p><p>Cette histoire raconte comment les ancêtres ont fondé cette terre et ont établi les premières traditions qui régissent encore aujourd'hui la vie des habitants de {$regionName}.</p><h2>La Morale</h2><p>Cette légende nous enseigne l'importance de respecter nos ancêtres et de préserver nos traditions.</p>",
                'premium' => true,
                'prix' => 2500
            ],
            'Conte / Fable' => [
                'titre' => "Conte Traditionnel de {$regionName}",
                'texte' => "<h2>Le Conte</h2><p>Dans la région de {$regionName}, on raconte cette histoire aux enfants pour leur enseigner les valeurs importantes de la vie...</p><p>La morale de ce conte nous rappelle l'importance de la sagesse et du respect des traditions de {$regionName}.</p>",
                'premium' => false
            ],
            'Proverbe / Sagesse' => [
                'titre' => "Sagesse Ancestrale de {$regionName}",
                'texte' => "<h2>Collection de Proverbes</h2><ul><li><strong>Proverbe 1</strong> - Signification et enseignement</li><li><strong>Proverbe 2</strong> - Sagesse ancestrale</li><li><strong>Proverbe 3</strong> - Leçon de vie</li></ul><h2>L'Importance</h2><p>Ces proverbes transmettent la sagesse des ancêtres de {$regionName}.</p>",
                'premium' => true,
                'prix' => 1500
            ],
            'Chanson / Musique' => [
                'titre' => "Musique Traditionnelle de {$regionName}",
                'texte' => "<h2>Introduction</h2><p>La musique traditionnelle de {$regionName} est caractérisée par ses rythmes uniques et ses instruments spécifiques.</p><h2>Instruments</h2><p>Les instruments traditionnels de cette région incluent des tambours, des flûtes et des instruments à cordes uniques.</p>",
                'premium' => true,
                'prix' => 3000
            ],
            'Danse traditionnelle' => [
                'titre' => "Danses Traditionnelles de {$regionName}",
                'texte' => "<h2>Les Danses</h2><p>Les danses traditionnelles de {$regionName} sont un spectacle à voir absolument. Chaque mouvement a une signification profonde liée à l'histoire et à la culture locale.</p>",
                'premium' => false
            ],
            'Recette culinaire' => [
                'titre' => "Spécialité Culinaire de {$regionName}",
                'texte' => "<h2>La Recette</h2><p>Découvrez la recette traditionnelle de {$regionName}, un plat qui ravit les papilles depuis des générations.</p><h2>Ingrédients</h2><ul><li>Ingrédient 1</li><li>Ingrédient 2</li><li>Ingrédient 3</li></ul><h2>Préparation</h2><p>Étapes de préparation de ce délicieux plat...</p>",
                'premium' => true,
                'prix' => 2000
            ],
            'Artisanat' => [
                'titre' => "Artisanat Traditionnel de {$regionName}",
                'texte' => "<h2>L'Artisanat</h2><p>L'artisanat de {$regionName} est reconnu pour sa qualité et son authenticité. Les artisans transmettent leurs savoir-faire de génération en génération.</p>",
                'premium' => true,
                'prix' => 4000
            ],
            'Cérémonie / Rituel' => [
                'titre' => "Cérémonies et Rituels de {$regionName}",
                'texte' => "<h2>Les Cérémonies</h2><p>Les cérémonies traditionnelles de {$regionName} sont des moments importants de la vie communautaire. Elles rythment l'année et renforcent les liens sociaux.</p>",
                'premium' => false
            ],
            'Personnage historique' => [
                'titre' => "Personnage Historique de {$regionName}",
                'texte' => "<h2>Biographie</h2><p>Ce personnage historique de {$regionName} a marqué l'histoire de la région par ses actions et sa vision.</p><h2>Héritage</h2><p>Son héritage continue d'influencer la région aujourd'hui.</p>",
                'premium' => true,
                'prix' => 3500
            ],
            'Lieu culturel' => [
                'titre' => "Site Culturel de {$regionName}",
                'texte' => "<h2>Le Site</h2><p>Ce site culturel de {$regionName} est un lieu emblématique qui témoigne de la richesse historique et culturelle de la région.</p><h2>Visite</h2><p>Ce lieu mérite une visite pour comprendre l'histoire de {$regionName}.</p>",
                'premium' => false
            ],
            'Poème' => [
                'titre' => "Poème sur {$regionName}",
                'texte' => "<div style=\"font-style: italic; line-height: 2;\"><p><strong>Ô {$regionName}, terre de mes ancêtres,</strong></p><p>Où la culture fleurit comme les fleurs,</p><p>Où les traditions vivent éternellement,</p><p>Où l'histoire se raconte chaque jour.</p></div>",
                'premium' => true,
                'prix' => 1000
            ],
            'Vidéo' => [
                'titre' => "Documentaire sur {$regionName}",
                'texte' => "<h2>Documentaire</h2><p>Ce documentaire explore la culture, l'histoire et les traditions de la région de {$regionName}.</p><p><em>Note : Ce contenu inclurait une vidéo documentaire complète une fois les médias uploadés.</em></p>",
                'premium' => true,
                'prix' => 5000
            ],
            'Galerie photo' => [
                'titre' => "Galerie Photo de {$regionName}",
                'texte' => "<h2>Galerie</h2><p>Cette galerie présente les plus beaux moments et lieux de la région de {$regionName}.</p><p><em>Note : Cette galerie inclurait des photos haute résolution une fois les médias uploadés.</em></p>",
                'premium' => false
            ],
            'Document' => [
                'titre' => "Document Historique de {$regionName}",
                'texte' => "<h2>Document</h2><p>Ce document historique retrace l'histoire de la région de {$regionName} à travers les archives et les témoignages.</p><p><em>Note : Ce contenu inclurait le document PDF complet une fois les médias uploadés.</em></p>",
                'premium' => true,
                'prix' => 2500
            ],
        ];
        
        return $templates[$typeName] ?? [
            'titre' => "{$typeName} de {$regionName}",
            'texte' => "<h2>Contenu</h2><p>Découvrez ce contenu de type \"{$typeName}\" de la région de {$regionName}.</p>",
            'premium' => false
        ];
    }
    
    // Méthodes pour chaque région avec des contenus spécifiques
    private function getContenusAlibori()
    {
        return [
            'Article' => [
                'titre' => 'Alibori : La Porte du Nord Béninois',
                'texte' => '<h2>Introduction</h2><p>Alibori, le plus grand département du Bénin, est une région frontalière riche en histoire et en traditions. Située au nord, elle abrite une diversité culturelle remarquable.</p><h2>Géographie et Population</h2><p>Avec une superficie de 25 800 km² et une population de plus de 860 000 habitants, Alibori est caractérisée par ses vastes plaines et sa savane.</p><h2>Culture et Traditions</h2><p>La région est connue pour ses festivals traditionnels, notamment la fête du Gani qui célèbre la culture Bariba.</p>',
                'premium' => false
            ],
            'Histoire / Légende' => [
                'titre' => 'La Légende de Kaba, Fondateur d\'Alibori',
                'texte' => '<h2>La Légende</h2><p>Il était une fois, un grand guerrier nommé Kaba qui, guidé par les esprits, fonda le premier royaume dans la région d\'Alibori. La légende raconte qu\'il reçut une épée magique des ancêtres.</p><h2>L\'Héritage</h2><p>Cette légende explique l\'origine des traditions guerrières et de la structure sociale qui existe encore aujourd\'hui dans la région.</p>',
                'premium' => true,
                'prix' => 2500
            ],
            'Conte / Fable' => [
                'titre' => 'Le Lion et le Lièvre : Conte d\'Alibori',
                'texte' => '<h2>Le Conte</h2><p>Dans la savane d\'Alibori, un lion arrogant défia tous les animaux. Mais un petit lièvre rusé trouva un moyen de le vaincre par la ruse plutôt que par la force.</p><h2>La Morale</h2><p>Ce conte enseigne que l\'intelligence et la sagesse triomphent toujours de la force brute.</p>',
                'premium' => false
            ],
            'Recette culinaire' => [
                'titre' => 'Tchoukoukou : Plat Emblématique d\'Alibori',
                'texte' => '<h2>La Recette</h2><p>Le Tchoukoukou est un plat traditionnel d\'Alibori à base de mil, de haricots et d\'épices locales.</p><h2>Ingrédients</h2><ul><li>500g de mil</li><li>300g de haricots</li><li>Oignons, tomates, piments</li><li>Épices locales</li></ul><h2>Préparation</h2><p>Faire cuire le mil et les haricots séparément, puis les mélanger avec les légumes et épices. Laisser mijoter jusqu\'à obtenir une consistance crémeuse.</p>',
                'premium' => true,
                'prix' => 2000
            ],
        ];
    }
    
    private function getContenusAtakora()
    {
        return [
            'Article' => [
                'titre' => 'Atakora : Montagnes et Traditions',
                'texte' => '<h2>Introduction</h2><p>L\'Atakora, région montagneuse du nord-ouest, est réputée pour ses paysages spectaculaires et sa riche culture traditionnelle.</p><h2>Géographie</h2><p>La chaîne de l\'Atakora domine la région avec ses sommets qui offrent des vues panoramiques exceptionnelles.</p>',
                'premium' => false
            ],
            'Danse traditionnelle' => [
                'titre' => 'La Danse Tchinkoumé d\'Atakora',
                'texte' => '<h2>Origines</h2><p>Le Tchinkoumé est une danse sacrée de l\'Atakora, pratiquée lors des cérémonies importantes. Les danseurs portent des costumes traditionnels colorés.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusAtlantique()
    {
        return [
            'Article' => [
                'titre' => 'Atlantique : Cœur Économique du Bénin',
                'texte' => '<h2>Introduction</h2><p>L\'Atlantique est le département le plus peuplé du Bénin, abritant la capitale économique et de nombreux sites culturels importants.</p>',
                'premium' => false
            ],
            'Lieu culturel' => [
                'titre' => 'Le Temple des Pythons d\'Ouidah',
                'texte' => '<h2>Le Temple</h2><p>Le Temple des Pythons est un site sacré unique où les pythons sont vénérés et protégés. C\'est un lieu de pèlerinage important.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusBorgou()
    {
        return [
            'Article' => [
                'titre' => 'Borgou : Terre des Rois',
                'texte' => '<h2>Introduction</h2><p>Le Borgou est une région historique qui fut le berceau de plusieurs royaumes puissants. Elle conserve de nombreuses traditions royales.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusCollines()
    {
        return [
            'Article' => [
                'titre' => 'Collines : Paysages et Culture',
                'texte' => '<h2>Introduction</h2><p>Le département des Collines doit son nom à ses paysages vallonnés. C\'est une région agricole importante avec une culture riche.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusDonga()
    {
        return [
            'Article' => [
                'titre' => 'Donga : Traditions et Modernité',
                'texte' => '<h2>Introduction</h2><p>La Donga est une région qui allie harmonieusement traditions ancestrales et développement moderne.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusKouffo()
    {
        return [
            'Article' => [
                'titre' => 'Kouffo : Patrimoine Culturel',
                'texte' => '<h2>Introduction</h2><p>Le Kouffo est riche en sites historiques et en traditions culturelles préservées depuis des siècles.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusLittoral()
    {
        return [
            'Article' => [
                'titre' => 'Littoral : Cotonou, Métropole Culturelle',
                'texte' => '<h2>Introduction</h2><p>Le Littoral, centré sur Cotonou, est le cœur économique et culturel du Bénin. Il concentre musées, festivals et événements culturels majeurs.</p>',
                'premium' => false
            ],
            'Lieu culturel' => [
                'titre' => 'Le Musée d\'Histoire de Ouidah',
                'texte' => '<h2>Le Musée</h2><p>Ce musée retrace l\'histoire de la traite des esclaves et de la culture béninoise. C\'est un lieu de mémoire essentiel.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusMono()
    {
        return [
            'Article' => [
                'titre' => 'Mono : Traditions Lacustres',
                'texte' => '<h2>Introduction</h2><p>Le Mono, avec ses lacs et ses cours d\'eau, possède une culture unique liée à la vie aquatique et aux traditions de pêche.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusOueme()
    {
        return [
            'Article' => [
                'titre' => 'Ouémé : Histoire et Culture',
                'texte' => '<h2>Introduction</h2><p>L\'Ouémé, avec Porto-Novo comme chef-lieu, est riche en histoire et en traditions culturelles diverses.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusPlateau()
    {
        return [
            'Article' => [
                'titre' => 'Plateau : Terre Fertile et Culture',
                'texte' => '<h2>Introduction</h2><p>Le Plateau est une région agricole prospère avec une culture riche et des traditions bien préservées.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusZou()
    {
        return [
            'Article' => [
                'titre' => 'Zou : Cœur Historique du Bénin',
                'texte' => '<h2>Introduction</h2><p>Le Zou abrite Abomey, ancienne capitale du royaume du Dahomey. C\'est une région d\'une richesse historique exceptionnelle.</p>',
                'premium' => false
            ],
            'Lieu culturel' => [
                'titre' => 'Les Palais Royaux d\'Abomey',
                'texte' => '<h2>Les Palais</h2><p>Classés au patrimoine mondial de l\'UNESCO, les Palais Royaux d\'Abomey témoignent de la grandeur du royaume du Dahomey.</p>',
                'premium' => false
            ],
        ];
    }
    
    private function getContenusNord()
    {
        return [
            'Article' => [
                'titre' => 'Nord : Diversité Culturelle',
                'texte' => '<h2>Introduction</h2><p>Le Nord du Bénin regroupe plusieurs départements avec une diversité culturelle remarquable et des traditions préservées.</p>',
                'premium' => false
            ],
        ];
    }
}

