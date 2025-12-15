<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contenu;

echo "=== Correction du statut des contenus ===\n\n";

// Mettre tous les contenus en 'valide' sauf ceux explicitement rejetés
$contenus = Contenu::all();
$fixed = 0;
$skipped = 0;

foreach ($contenus as $contenu) {
    $statut = trim(strtolower($contenu->statut ?? ''));
    
    // Si le statut n'est pas 'valide' et n'est pas explicitement 'rejete', le corriger
    if ($statut !== 'valide' && $statut !== 'rejete') {
        echo "Correction: [{$contenu->statut}] -> [valide] - {$contenu->titre}\n";
        $contenu->statut = 'valide';
        $contenu->save();
        $fixed++;
    } else {
        $skipped++;
    }
}

echo "\n=== Résumé ===\n";
echo "Contenus corrigés: {$fixed}\n";
echo "Contenus laissés tels quels: {$skipped}\n";

// Vérifier les relations
echo "\n=== Vérification des relations ===\n";
$contenusSansRegion = Contenu::whereNull('id_region')->orWhere('id_region', 0)->count();
$contenusSansLangue = Contenu::whereNull('id_langue')->orWhere('id_langue', 0)->count();
$contenusSansType = Contenu::whereNull('id_type_contenu')->orWhere('id_type_contenu', 0)->count();

echo "Contenus sans région: {$contenusSansRegion}\n";
echo "Contenus sans langue: {$contenusSansLangue}\n";
echo "Contenus sans type: {$contenusSansType}\n";

if ($contenusSansRegion > 0 || $contenusSansLangue > 0 || $contenusSansType > 0) {
    echo "\n⚠️  Certains contenus ont des relations manquantes !\n";
}















