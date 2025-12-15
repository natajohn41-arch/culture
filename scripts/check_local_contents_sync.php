<?php
/**
 * Script pour vérifier la synchronisation entre les contenus locaux et la base de données
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
echo "║  Vérification de la Synchronisation des Contenus            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Compter les contenus dans la base de données
$totalContenus = Contenu::count();
echo "📊 Contenus dans la base de données: {$totalContenus}\n\n";

// Vérifier le seeder
$seederFile = database_path('seeders/exports/AllContentsSeeder.php');
if (file_exists($seederFile)) {
    $seederContent = file_get_contents($seederFile);
    // Compter les entrées dans le tableau
    preg_match_all("/\d+\s*=>/", $seederContent, $matches);
    $contenusDansSeeder = count($matches[0]);
    echo "📁 Contenus dans le seeder AllContentsSeeder: {$contenusDansSeeder}\n\n";
    
    if ($totalContenus >= $contenusDansSeeder) {
        echo "✅ Tous les contenus du seeder sont dans la base de données !\n";
        if ($totalContenus > $contenusDansSeeder) {
            $difference = $totalContenus - $contenusDansSeeder;
            echo "   ℹ️  Il y a {$difference} contenu(s) supplémentaire(s) dans la base (créés par d'autres seeders)\n";
        }
    } else {
        $manquants = $contenusDansSeeder - $totalContenus;
        echo "⚠️  {$manquants} contenu(s) du seeder ne sont pas dans la base de données.\n";
        echo "   Exécutez: php scripts/import_local_contents.php\n";
    }
} else {
    echo "⚠️  Le seeder AllContentsSeeder n'existe pas.\n";
    echo "   Exécutez d'abord: php scripts/export_all_contents.php\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📈 Statistiques par région:\n";
$contenusParRegion = Contenu::selectRaw('regions.nom_region, COUNT(*) as total')
    ->join('regions', 'contenus.id_region', '=', 'regions.id_region')
    ->groupBy('regions.nom_region')
    ->orderBy('total', 'desc')
    ->get();

foreach ($contenusParRegion as $stat) {
    echo "   - {$stat->nom_region}: {$stat->total} contenu(s)\n";
}

echo "\n📈 Statistiques par type:\n";
$contenusParType = Contenu::selectRaw('type_contenus.nom_contenu, COUNT(*) as total')
    ->join('type_contenus', 'contenus.id_type_contenu', '=', 'type_contenus.id_type_contenu')
    ->groupBy('type_contenus.nom_contenu')
    ->orderBy('total', 'desc')
    ->get();

foreach ($contenusParType as $stat) {
    echo "   - {$stat->nom_contenu}: {$stat->total} contenu(s)\n";
}

echo "\n📈 Statistiques par statut:\n";
$contenusParStatut = Contenu::selectRaw('statut, COUNT(*) as total')
    ->groupBy('statut')
    ->orderBy('total', 'desc')
    ->get();

foreach ($contenusParStatut as $stat) {
    echo "   - {$stat->statut}: {$stat->total} contenu(s)\n";
}

echo "\n✅ Vérification terminée !\n";






