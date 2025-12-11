<?php
/**
 * Script pour importer TOUS les contenus locaux dans la base de données
 * Ce script exécute le seeder AllContentsSeeder pour synchroniser les contenus
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contenu;
use Database\Seeders\Exports\AllContentsSeeder;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Import des Contenus Locaux dans la Base de Données        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Compter les contenus avant l'import
$contenusAvant = Contenu::count();
echo "📊 Contenus dans la base AVANT l'import: {$contenusAvant}\n\n";

// Vérifier si le seeder existe
if (!class_exists(\Database\Seeders\Exports\AllContentsSeeder::class)) {
    echo "❌ Erreur: Le seeder AllContentsSeeder n'existe pas.\n";
    echo "   Exécutez d'abord: php scripts/export_all_contents.php\n";
    exit(1);
}

echo "🔄 Import en cours...\n\n";

// Exécuter le seeder via artisan
try {
    echo "   Exécution du seeder AllContentsSeeder...\n";
    $exitCode = \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'Database\\Seeders\\Exports\\AllContentsSeeder'
    ]);
    
    $output = \Illuminate\Support\Facades\Artisan::output();
    if ($output) {
        echo $output;
    }
    
    // Compter les contenus après l'import
    $contenusApres = Contenu::count();
    $contenusAjoutes = $contenusApres - $contenusAvant;
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 Résumé:\n";
    echo "   - Contenus avant: {$contenusAvant}\n";
    echo "   - Contenus après: {$contenusApres}\n";
    echo "   - Contenus ajoutés: {$contenusAjoutes}\n";
    echo "\n✅ Import terminé avec succès !\n";
    
} catch (\Exception $e) {
    echo "\n❌ Erreur lors de l'import: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

