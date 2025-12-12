<?php
/**
 * Script pour tester toutes les routes et vérifier qu'elles sont bien définies
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Test de Toutes les Routes                                   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Liste des routes critiques à vérifier
$routesCritiques = [
    'dashboard',
    'contenus.create',
    'contenus.index',
    'contenus.a-valider',
    'utilisateurs.create',
    'utilisateurs.index',
    'regions.index',
    'langues.index',
    'contenus.public',
    'login',
    'logout',
    'accueil',
];

$routesTrouvees = [];
$routesManquantes = [];

echo "🔍 Vérification des routes critiques...\n\n";

foreach ($routesCritiques as $routeName) {
    try {
        $route = Route::getRoutes()->getByName($routeName);
        if ($route) {
            $routesTrouvees[] = $routeName;
            echo "   ✅ {$routeName}\n";
        } else {
            $routesManquantes[] = $routeName;
            echo "   ❌ {$routeName} - NON TROUVÉE\n";
        }
    } catch (\Exception $e) {
        $routesManquantes[] = $routeName;
        echo "   ❌ {$routeName} - ERREUR: " . $e->getMessage() . "\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Résumé:\n";
echo "   - Routes trouvées: " . count($routesTrouvees) . "\n";
echo "   - Routes manquantes: " . count($routesManquantes) . "\n";

if (count($routesManquantes) > 0) {
    echo "\n⚠️  Routes manquantes:\n";
    foreach ($routesManquantes as $route) {
        echo "   - {$route}\n";
    }
    echo "\n❌ Des routes critiques sont manquantes !\n";
    exit(1);
} else {
    echo "\n✅ Toutes les routes critiques sont définies !\n";
}

// Vérifier les routes utilisées dans les vues
echo "\n🔍 Vérification des routes dans les vues...\n\n";

$vuesDashboard = [
    'resources/views/dashboard/admin-content.blade.php',
    'resources/views/dashboard/moderator-content.blade.php',
    'resources/views/dashboard/author-content.blade.php',
    'resources/views/dashboard/user-content.blade.php',
];

$routesDansVues = [];

foreach ($vuesDashboard as $vue) {
    if (file_exists($vue)) {
        $contenu = file_get_contents($vue);
        preg_match_all("/route\(['\"]([^'\"]+)['\"]\)/", $contenu, $matches);
        if (!empty($matches[1])) {
            $routesDansVues = array_merge($routesDansVues, $matches[1]);
        }
    }
}

$routesDansVues = array_unique($routesDansVues);
$routesVuesManquantes = [];

foreach ($routesDansVues as $routeName) {
    try {
        $route = Route::getRoutes()->getByName($routeName);
        if (!$route) {
            $routesVuesManquantes[] = $routeName;
            echo "   ❌ {$routeName} (utilisée dans les vues mais non définie)\n";
        }
    } catch (\Exception $e) {
        $routesVuesManquantes[] = $routeName;
        echo "   ❌ {$routeName} - ERREUR: " . $e->getMessage() . "\n";
    }
}

if (count($routesVuesManquantes) > 0) {
    echo "\n⚠️  Routes utilisées dans les vues mais non définies:\n";
    foreach ($routesVuesManquantes as $route) {
        echo "   - {$route}\n";
    }
    echo "\n❌ Des routes utilisées dans les vues sont manquantes !\n";
    exit(1);
} else {
    echo "\n✅ Toutes les routes utilisées dans les vues sont définies !\n";
}

echo "\n✅ Tous les tests de routes sont passés avec succès !\n";

