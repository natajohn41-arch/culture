<?php
/**
 * Script pour exporter TOUS les contenus de la base de données locale
 * et créer un seeder qui peut les importer sur le serveur de production
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\TypeContenu;
use App\Models\Utilisateur;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Export de TOUS les Contenus Locaux                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$contenus = Contenu::with(['region', 'langue', 'typeContenu', 'auteur'])->get();

echo "Total contenus à exporter: " . $contenus->count() . "\n\n";

// Créer le répertoire d'export s'il n'existe pas
$exportDir = database_path('seeders/exports');
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

// Préparer les données pour le seeder
$contenusData = [];
$regionsMap = [];
$languesMap = [];
$typesMap = [];
$auteursMap = [];

// Créer les maps pour les références
foreach (Region::all() as $region) {
    $regionsMap[$region->nom_region] = $region->id_region ?? $region->id;
}

foreach (Langue::all() as $langue) {
    $languesMap[$langue->nom_langue] = $langue->id_langue ?? $langue->id;
}

foreach (TypeContenu::all() as $type) {
    $typesMap[$type->nom_contenu] = $type->id_type_contenu ?? $type->id;
}

foreach (Utilisateur::all() as $user) {
    $auteursMap[$user->email] = $user->id_utilisateur ?? $user->id;
}

// Exporter chaque contenu
foreach ($contenus as $contenu) {
    $regionName = $contenu->region->nom_region ?? 'Nord';
    $langueName = $contenu->langue->nom_langue ?? 'Français';
    $typeName = $contenu->typeContenu->nom_contenu ?? 'Article';
    $auteurEmail = $contenu->auteur->email ?? 'admin@example.test';
    
    $contenusData[] = [
        'titre' => $contenu->titre,
        'texte' => $contenu->texte,
        'region' => $regionName,
        'langue' => $langueName,
        'type' => $typeName,
        'auteur' => $auteurEmail,
        'statut' => $contenu->statut,
        'est_premium' => (bool)$contenu->est_premium,
        'prix' => $contenu->prix,
        'date_creation' => $contenu->date_creation ? $contenu->date_creation->format('Y-m-d H:i:s') : null,
        'date_validation' => $contenu->date_validation ? $contenu->date_validation->format('Y-m-d H:i:s') : null,
    ];
}

// Générer le code du seeder
$seederCode = "<?php

namespace Database\Seeders\Exports;

use Illuminate\Database\Seeder;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\TypeContenu;
use App\Models\Utilisateur;
use Carbon\Carbon;

class AllContentsSeeder extends Seeder
{
    /**
     * Importe TOUS les contenus de la base locale
     */
    public function run(): void
    {
        \$contenus = " . var_export($contenusData, true) . ";
        
        \$regionsMap = [];
        \$languesMap = [];
        \$typesMap = [];
        \$auteursMap = [];
        
        // Créer les maps
        foreach (Region::all() as \$region) {
            \$regionsMap[\$region->nom_region] = \$region->id_region ?? \$region->id;
        }
        
        foreach (Langue::all() as \$langue) {
            \$languesMap[\$langue->nom_langue] = \$langue->id_langue ?? \$langue->id;
        }
        
        foreach (TypeContenu::all() as \$type) {
            \$typesMap[\$type->nom_contenu] = \$type->id_type_contenu ?? \$type->id;
        }
        
        foreach (Utilisateur::all() as \$user) {
            \$auteursMap[\$user->email] = \$user->id_utilisateur ?? \$user->id;
        }
        
        \$created = 0;
        \$skipped = 0;
        
        foreach (\$contenus as \$data) {
            // Trouver les IDs
            \$regionId = \$regionsMap[\$data['region']] ?? null;
            \$langueId = \$languesMap[\$data['langue']] ?? null;
            \$typeId = \$typesMap[\$data['type']] ?? null;
            \$auteurId = \$auteursMap[\$data['auteur']] ?? null;
            
            if (!\$regionId || !\$langueId || !\$typeId || !\$auteurId) {
                \$this->command->warn('Contenu ignoré: ' . \$data['titre'] . ' (dépendances manquantes)');
                \$skipped++;
                continue;
            }
            
            // Vérifier si le contenu existe déjà
            \$exists = Contenu::where('titre', \$data['titre'])
                ->where('id_region', \$regionId)
                ->where('id_type_contenu', \$typeId)
                ->exists();
            
            if (\$exists) {
                \$skipped++;
                continue;
            }
            
            // Créer le contenu
            try {
                Contenu::create([
                    'titre' => \$data['titre'],
                    'texte' => \$data['texte'],
                    'id_region' => \$regionId,
                    'id_langue' => \$langueId,
                    'id_type_contenu' => \$typeId,
                    'id_auteur' => \$auteurId,
                    'statut' => 'valide', // Forcer le statut à 'valide'
                    'date_creation' => \$data['date_creation'] ? Carbon::parse(\$data['date_creation']) : Carbon::now(),
                    'date_validation' => \$data['date_validation'] ? Carbon::parse(\$data['date_validation']) : Carbon::now(),
                    'id_moderateur' => \$auteurId,
                    'est_premium' => \$data['est_premium'],
                    'prix' => \$data['prix'],
                ]);
                \$created++;
            } catch (\\Exception \$e) {
                \$this->command->error('Erreur pour: ' . \$data['titre'] . ' - ' . \$e->getMessage());
                \$skipped++;
            }
        }
        
        \$this->command->info('✅ ' . \$created . ' contenus créés !');
        \$this->command->info('⏭️  ' . \$skipped . ' contenus ignorés (déjà existants ou erreurs)');
    }
}
";

// Sauvegarder le seeder
$seederFile = $exportDir . '/AllContentsSeeder.php';
file_put_contents($seederFile, $seederCode);

echo "✅ Export terminé !\n";
echo "📁 Fichier créé: {$seederFile}\n";
echo "📊 Total contenus exportés: " . count($contenusData) . "\n\n";

echo "Pour importer sur le serveur de production, exécutez :\n";
echo "  php artisan db:seed --class=Database\\Seeders\\Exports\\AllContentsSeeder\n\n";








