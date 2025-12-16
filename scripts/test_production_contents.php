<?php
/**
 * Script pour tester que les contenus sont bien publiés sur la production
 * 
 * Ce script vérifie :
 * - Le nombre de contenus valides
 * - La répartition par région
 * - La répartition par type
 * - Que les contenus sont accessibles publiquement
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contenu;
use App\Models\Region;
use App\Models\TypeContenu;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Test de Publication des Contenus en Production             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Compter les contenus
$totalContenus = Contenu::count();
$contenusValides = Contenu::where('statut', 'valide')->count();
$contenusEnAttente = Contenu::where('statut', 'en_attente')->count();
$contenusRejetes = Contenu::where('statut', 'rejete')->count();

echo "📊 Statistiques générales:\n";
echo "   - Total contenus: {$totalContenus}\n";
echo "   - Contenus valides (publiés): {$contenusValides}\n";
echo "   - Contenus en attente: {$contenusEnAttente}\n";
echo "   - Contenus rejetés: {$contenusRejetes}\n\n";

// Vérifier le minimum attendu
$minimumAttendu = 200;
if ($contenusValides < $minimumAttendu) {
    echo "⚠️  ATTENTION: Seulement {$contenusValides} contenus valides (minimum attendu: {$minimumAttendu})\n";
    echo "   Il semble que l'import n'ait pas fonctionné correctement.\n\n";
} else {
    echo "✅ Nombre de contenus valides OK ({$contenusValides} >= {$minimumAttendu})\n\n";
}

// Répartition par région
echo "📈 Répartition par région:\n";
$contenusParRegion = Contenu::selectRaw('regions.nom_region, COUNT(*) as total')
    ->join('regions', 'contenus.id_region', '=', 'regions.id_region')
    ->where('contenus.statut', 'valide')
    ->groupBy('regions.nom_region')
    ->orderBy('total', 'desc')
    ->get();

$regionsAvecContenus = 0;
foreach ($contenusParRegion as $stat) {
    echo "   - {$stat->nom_region}: {$stat->total} contenu(s)\n";
    if ($stat->total > 0) {
        $regionsAvecContenus++;
    }
}

$totalRegions = Region::count();
echo "\n   Régions avec contenus: {$regionsAvecContenus} / {$totalRegions}\n";

if ($regionsAvecContenus < $totalRegions) {
    echo "   ⚠️  Certaines régions n'ont pas de contenus\n";
} else {
    echo "   ✅ Toutes les régions ont des contenus\n";
}

// Répartition par type
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

// Vérifier quelques contenus spécifiques
echo "\n🔍 Vérification de quelques contenus spécifiques:\n";
$contenusTest = [
    'La Culture Béninoise : Un Patrimoine Riche et Diversifié',
    'La Légende de la Reine Tassi Hangbé',
    'Le Lièvre et la Tortue : Version Béninoise',
];

foreach ($contenusTest as $titre) {
    $contenu = Contenu::where('titre', $titre)
        ->where('statut', 'valide')
        ->first();
    
    if ($contenu) {
        echo "   ✅ '{$titre}' - Publié\n";
    } else {
        echo "   ❌ '{$titre}' - Non trouvé ou non publié\n";
    }
}

// Résumé final
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Résumé:\n";

$tousLesTestsPassent = true;

if ($contenusValides < $minimumAttendu) {
    echo "   ❌ Nombre de contenus insuffisant\n";
    $tousLesTestsPassent = false;
} else {
    echo "   ✅ Nombre de contenus suffisant\n";
}

if ($regionsAvecContenus < $totalRegions) {
    echo "   ⚠️  Certaines régions n'ont pas de contenus\n";
} else {
    echo "   ✅ Toutes les régions ont des contenus\n";
}

if ($tousLesTestsPassent) {
    echo "\n✅ Tous les tests sont passés ! Les contenus sont bien publiés.\n";
    echo "🌐 Visitez https://culture-1-19zy.onrender.com/ pour voir les contenus\n";
} else {
    echo "\n⚠️  Certains tests ont échoué. Vérifiez l'import des contenus.\n";
    echo "💡 Exécutez: php scripts/deploy_all_contents_to_production.php\n";
}











