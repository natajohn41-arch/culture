<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contenu;

echo "=== Vérification du statut des contenus ===\n\n";

$total = Contenu::count();
$valides = Contenu::where('statut', 'valide')->count();
$rejetes = Contenu::where('statut', 'rejete')->count();
$enAttente = Contenu::where('statut', 'en_attente')->orWhere('statut', 'en attente')->count();
$autres = Contenu::whereNotIn('statut', ['valide', 'rejete', 'en_attente', 'en attente'])->count();

echo "Total contenus: {$total}\n";
echo "Contenus validés: {$valides}\n";
echo "Contenus rejetés: {$rejetes}\n";
echo "Contenus en attente: {$enAttente}\n";
echo "Autres statuts: {$autres}\n\n";

// Vérifier les statuts uniques
echo "=== Statuts uniques dans la base ===\n";
$statuts = Contenu::selectRaw('statut, COUNT(*) as count')
    ->groupBy('statut')
    ->get();

foreach ($statuts as $stat) {
    echo "  - '{$stat->statut}': {$stat->count} contenus\n";
}

// Vérifier les contenus récents
echo "\n=== 10 contenus les plus récents ===\n";
$recent = Contenu::orderByDesc('date_creation')->take(10)->get();
foreach ($recent as $c) {
    echo "  - [{$c->statut}] {$c->titre} (ID: {$c->id_contenu})\n";
}














