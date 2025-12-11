<?php
/**
 * Script pour déployer tous les contenus sur le serveur de production
 * 
 * Usage: php scripts/deploy_contents_to_production.php
 * 
 * Ce script :
 * 1. Vérifie que toutes les dépendances sont en place
 * 2. Crée tous les contenus manquants
 * 3. S'assure que tous les contenus ont le statut 'valide'
 * 4. Affiche un rapport complet
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Region;
use App\Models\TypeContenu;
use App\Models\Contenu;
use App\Models\Langue;
use App\Models\Utilisateur;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Déploiement des Contenus sur le Serveur de Production     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Vérifications préalables
echo "=== Vérifications préalables ===\n";

$regions = Region::all();
if ($regions->isEmpty()) {
    echo "❌ Aucune région trouvée. Exécutez d'abord: php artisan db:seed --class=RegionSeeder\n";
    exit(1);
}
echo "✅ Régions: " . $regions->count() . "\n";

$types = TypeContenu::all();
if ($types->isEmpty()) {
    echo "❌ Aucun type de contenu trouvé. Exécutez d'abord: php artisan db:seed --class=TypeContenuSeeder\n";
    exit(1);
}
echo "✅ Types de contenus: " . $types->count() . "\n";

$langues = Langue::all();
if ($langues->isEmpty()) {
    echo "❌ Aucune langue trouvée. Exécutez d'abord: php artisan db:seed --class=LangueSeeder\n";
    exit(1);
}
echo "✅ Langues: " . $langues->count() . "\n";

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
    echo "❌ Aucun utilisateur trouvé. Exécutez d'abord: php artisan db:seed --class=UsersPerRoleSeeder\n";
    exit(1);
}

$auteur = $auteurs->first();
$langue = $langues->first();

echo "✅ Auteur: {$auteur->email}\n";
echo "✅ Langue par défaut: {$langue->nom_langue}\n\n";

// Exécuter le seeder
echo "=== Exécution du seeder EnhancedRegionContentSeeder ===\n";
$seeder = new \Database\Seeders\EnhancedRegionContentSeeder();
$seeder->setCommand(new class {
    public function info($message) { echo "   " . $message . "\n"; }
    public function warn($message) { echo "   ⚠️  " . $message . "\n"; }
    public function error($message) { echo "   ❌ " . $message . "\n"; }
});
$seeder->run();

echo "\n=== Vérification finale ===\n";
$totalValides = Contenu::where('statut', 'valide')->count();
$totalParRegion = [];

foreach ($regions as $region) {
    $regionId = $region->id_region ?? $region->id;
    $count = Contenu::where('id_region', $regionId)
        ->where('statut', 'valide')
        ->count();
    $totalParRegion[] = "  - {$region->nom_region}: {$count} contenus";
}

echo "Total contenus validés: {$totalValides}\n";
echo "Répartition par région:\n";
foreach ($totalParRegion as $stat) {
    echo $stat . "\n";
}

echo "\n✅ Déploiement terminé avec succès !\n";
echo "Les contenus sont maintenant disponibles sur le site.\n";





