<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Region;
use App\Models\TypeContenu;
use App\Models\Contenu;
use App\Models\Langue;
use App\Models\Utilisateur;
use Carbon\Carbon;

echo "=== Création forcée de tous les contenus ===\n\n";

$regions = Region::all();
$types = TypeContenu::all();
$langues = Langue::all();

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
    echo "❌ Aucun utilisateur trouvé !\n";
    exit(1);
}

$auteur = $auteurs->first();
$langue = $langues->first();

echo "Auteur: {$auteur->email}\n";
echo "Langue: {$langue->nom_langue}\n";
echo "Régions: " . $regions->count() . "\n";
echo "Types: " . $types->count() . "\n\n";

$created = 0;
$skipped = 0;

// Templates de contenus
$templates = [
    'Article' => [
        'titre' => 'Patrimoine Culturel de {region}',
        'texte' => '<h2>Introduction</h2><p>Découvrez la richesse culturelle exceptionnelle de la région de {region}. Cette région possède un patrimoine unique qui mérite d\'être exploré et préservé.</p><h2>Histoire et Traditions</h2><p>Les traditions de {region} remontent à plusieurs siècles et continuent d\'influencer la vie quotidienne de ses habitants.</p>',
        'premium' => false
    ],
    'Histoire / Légende' => [
        'titre' => 'Légende Ancestrale de {region}',
        'texte' => '<h2>La Légende</h2><p>Il était une fois, dans la région de {region}, une légende qui se transmettait de génération en génération...</p>',
        'premium' => true,
        'prix' => 2500
    ],
    'Conte / Fable' => [
        'titre' => 'Conte Traditionnel de {region}',
        'texte' => '<h2>Le Conte</h2><p>Dans la région de {region}, on raconte cette histoire aux enfants pour leur enseigner les valeurs importantes de la vie...</p>',
        'premium' => false
    ],
    'Proverbe / Sagesse' => [
        'titre' => 'Sagesse Ancestrale de {region}',
        'texte' => '<h2>Collection de Proverbes</h2><ul><li><strong>Proverbe 1</strong> - Signification et enseignement</li><li><strong>Proverbe 2</strong> - Sagesse ancestrale</li></ul>',
        'premium' => true,
        'prix' => 1500
    ],
    'Chanson / Musique' => [
        'titre' => 'Musique Traditionnelle de {region}',
        'texte' => '<h2>Introduction</h2><p>La musique traditionnelle de {region} est caractérisée par ses rythmes uniques et ses instruments spécifiques.</p>',
        'premium' => true,
        'prix' => 3000
    ],
    'Danse traditionnelle' => [
        'titre' => 'Danses Traditionnelles de {region}',
        'texte' => '<h2>Les Danses</h2><p>Les danses traditionnelles de {region} sont un spectacle à voir absolument.</p>',
        'premium' => false
    ],
    'Recette culinaire' => [
        'titre' => 'Spécialité Culinaire de {region}',
        'texte' => '<h2>La Recette</h2><p>Découvrez la recette traditionnelle de {region}, un plat qui ravit les papilles depuis des générations.</p>',
        'premium' => true,
        'prix' => 2000
    ],
    'Artisanat' => [
        'titre' => 'Artisanat Traditionnel de {region}',
        'texte' => '<h2>L\'Artisanat</h2><p>L\'artisanat de {region} est reconnu pour sa qualité et son authenticité.</p>',
        'premium' => true,
        'prix' => 4000
    ],
    'Cérémonie / Rituel' => [
        'titre' => 'Cérémonies et Rituels de {region}',
        'texte' => '<h2>Les Cérémonies</h2><p>Les cérémonies traditionnelles de {region} sont des moments importants de la vie communautaire.</p>',
        'premium' => false
    ],
    'Personnage historique' => [
        'titre' => 'Personnage Historique de {region}',
        'texte' => '<h2>Biographie</h2><p>Ce personnage historique de {region} a marqué l\'histoire de la région par ses actions et sa vision.</p>',
        'premium' => true,
        'prix' => 3500
    ],
    'Lieu culturel' => [
        'titre' => 'Site Culturel de {region}',
        'texte' => '<h2>Le Site</h2><p>Ce site culturel de {region} est un lieu emblématique qui témoigne de la richesse historique et culturelle de la région.</p>',
        'premium' => false
    ],
    'Poème' => [
        'titre' => 'Poème sur {region}',
        'texte' => '<div style="font-style: italic;"><p><strong>Ô {region}, terre de mes ancêtres,</strong></p><p>Où la culture fleurit comme les fleurs...</p></div>',
        'premium' => true,
        'prix' => 1000
    ],
    'Vidéo' => [
        'titre' => 'Documentaire sur {region}',
        'texte' => '<h2>Documentaire</h2><p>Ce documentaire explore la culture, l\'histoire et les traditions de la région de {region}.</p>',
        'premium' => true,
        'prix' => 5000
    ],
    'Galerie photo' => [
        'titre' => 'Galerie Photo de {region}',
        'texte' => '<h2>Galerie</h2><p>Cette galerie présente les plus beaux moments et lieux de la région de {region}.</p>',
        'premium' => false
    ],
    'Document' => [
        'titre' => 'Document Historique de {region}',
        'texte' => '<h2>Document</h2><p>Ce document historique retrace l\'histoire de la région de {region} à travers les archives et les témoignages.</p>',
        'premium' => true,
        'prix' => 2500
    ],
];

foreach ($regions as $region) {
    $regionId = $region->id_region ?? $region->id;
    
    foreach ($types as $type) {
        $typeId = $type->id_type_contenu ?? $type->id;
        $typeName = $type->nom_contenu;
        
        // Vérifier si un contenu de ce type existe déjà pour cette région
        $existing = Contenu::where('id_region', $regionId)
            ->where('id_type_contenu', $typeId)
            ->first();
        
        if ($existing) {
            // S'assurer que le statut est 'valide'
            if ($existing->statut !== 'valide') {
                echo "Correction statut: {$existing->titre} -> valide\n";
                $existing->statut = 'valide';
                $existing->save();
            }
            $skipped++;
            continue;
        }
        
        // Récupérer le template
        $template = $templates[$typeName] ?? [
            'titre' => "{$typeName} de {region}",
            'texte' => "<h2>Contenu</h2><p>Découvrez ce contenu de type \"{$typeName}\" de la région de {region}.</p>",
            'premium' => false
        ];
        
        // Remplacer {region}
        $titre = str_replace('{region}', $region->nom_region, $template['titre']);
        $texte = str_replace('{region}', $region->nom_region, $template['texte']);
        
        // Créer le contenu
        try {
            Contenu::create([
                'titre' => $titre,
                'texte' => $texte,
                'id_region' => $regionId,
                'id_langue' => $langue->id_langue ?? $langue->id,
                'id_type_contenu' => $typeId,
                'id_auteur' => $auteur->id_utilisateur ?? $auteur->id,
                'statut' => 'valide',
                'date_creation' => Carbon::now()->subDays(rand(1, 90)),
                'date_validation' => Carbon::now()->subDays(rand(1, 90)),
                'id_moderateur' => $auteur->id_utilisateur ?? $auteur->id,
                'est_premium' => $template['premium'] ?? false,
                'prix' => $template['prix'] ?? null
            ]);
            $created++;
            echo "✅ Créé: {$titre}\n";
        } catch (\Exception $e) {
            echo "❌ Erreur pour {$titre}: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n=== Résumé ===\n";
echo "Contenus créés: {$created}\n";
echo "Contenus existants (vérifiés): {$skipped}\n";
echo "Total contenus validés: " . Contenu::where('statut', 'valide')->count() . "\n";















