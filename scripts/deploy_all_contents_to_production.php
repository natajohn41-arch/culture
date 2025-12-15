<?php
/**
 * Script pour déployer TOUS les contenus locaux sur le serveur de production
 * 
 * Ce script exécute le seeder AllContentsSeeder qui importe tous les contenus
 * de la base locale vers la production.
 * 
 * Usage sur Render:
 * php scripts/deploy_all_contents_to_production.php
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
use Illuminate\Support\Facades\Artisan;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Déploiement de TOUS les Contenus Locaux en Production    ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Vérifications préalables
echo "🔍 Vérifications préalables...\n\n";

$regions = Region::all();
if ($regions->isEmpty()) {
    echo "❌ Aucune région trouvée. Exécutez d'abord: php artisan db:seed --class=RegionSeeder\n";
    exit(1);
}
echo "   ✅ Régions: " . $regions->count() . "\n";

$langues = Langue::all();
if ($langues->isEmpty()) {
    echo "❌ Aucune langue trouvée. Exécutez d'abord: php artisan db:seed --class=LangueSeeder\n";
    exit(1);
}
echo "   ✅ Langues: " . $langues->count() . "\n";

$types = TypeContenu::all();
if ($types->isEmpty()) {
    echo "❌ Aucun type de contenu trouvé. Exécutez d'abord: php artisan db:seed --class=TypeContenuSeeder\n";
    exit(1);
}
echo "   ✅ Types de contenus: " . $types->count() . "\n";

$utilisateurs = Utilisateur::where('statut', 'actif')->get();
if ($utilisateurs->isEmpty()) {
    echo "❌ Aucun utilisateur actif trouvé. Exécutez d'abord: php artisan db:seed --class=UsersPerRoleSeeder\n";
    exit(1);
}
echo "   ✅ Utilisateurs actifs: " . $utilisateurs->count() . "\n";

// Compter les contenus avant l'import
$contenusAvant = Contenu::where('statut', 'valide')->count();
echo "\n📊 Contenus valides AVANT l'import: {$contenusAvant}\n\n";

// Vérifier si le seeder existe
if (!class_exists(\Database\Seeders\Exports\AllContentsSeeder::class)) {
    echo "❌ Erreur: Le seeder AllContentsSeeder n'existe pas.\n";
    echo "   Assurez-vous que le fichier database/seeders/exports/AllContentsSeeder.php existe.\n";
    exit(1);
}

echo "🔄 Import des contenus en cours...\n\n";

// Exécuter le seeder
try {
    $exitCode = Artisan::call('db:seed', [
        '--class' => 'Database\\Seeders\\Exports\\AllContentsSeeder'
    ]);
    
    $output = Artisan::output();
    if ($output) {
        echo $output;
    }
    
    if ($exitCode !== 0) {
        echo "\n❌ Erreur lors de l'import (code: {$exitCode})\n";
        exit(1);
    }
    
} catch (\Exception $e) {
    echo "\n❌ Erreur lors de l'import: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

// Compter les contenus après l'import
$contenusApres = Contenu::where('statut', 'valide')->count();
$contenusAjoutes = $contenusApres - $contenusAvant;

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Résumé de l'import:\n";
echo "   - Contenus valides avant: {$contenusAvant}\n";
echo "   - Contenus valides après: {$contenusApres}\n";
echo "   - Contenus ajoutés: {$contenusAjoutes}\n\n";

// Statistiques par région
echo "📈 Répartition par région:\n";
$contenusParRegion = Contenu::selectRaw('regions.nom_region, COUNT(*) as total')
    ->join('regions', 'contenus.id_region', '=', 'regions.id_region')
    ->where('contenus.statut', 'valide')
    ->groupBy('regions.nom_region')
    ->orderBy('total', 'desc')
    ->get();

foreach ($contenusParRegion as $stat) {
    echo "   - {$stat->nom_region}: {$stat->total} contenu(s)\n";
}

// Statistiques par type
echo "\n📈 Répartition par type:\n";
$contenusParType = Contenu::selectRaw('type_contenus.nom_contenu, COUNT(*) as total')
    ->join('type_contenus', 'contenus.id_type_contenu', '=', 'type_contenus.id_type_contenu')
    ->where('contenus.statut', 'valide')
    ->groupBy('type_contenus.nom_contenu')
    ->orderBy('total', 'desc')
    ->get();

foreach ($contenusParType as $stat) {
    echo "   - {$stat->nom_contenu}: {$stat->total} contenu(s)\n";
}

echo "\n✅ Déploiement terminé avec succès !\n";
echo "🌐 Les contenus sont maintenant disponibles sur le site.\n";
echo "\n💡 Pour vérifier, visitez: https://culture-1-19zy.onrender.com/\n";





