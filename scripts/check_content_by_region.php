<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Region;
use App\Models\TypeContenu;
use App\Models\Contenu;

echo "=== Vérification des contenus par région ===\n\n";

$regions = Region::all();
$types = TypeContenu::all();

echo "Total régions: " . $regions->count() . "\n";
echo "Total types de contenus: " . $types->count() . "\n";
echo "Total contenus: " . Contenu::count() . "\n\n";

echo "=== Détail par région ===\n";
foreach ($regions as $region) {
    $regionId = $region->id_region ?? $region->id;
    $count = Contenu::where('id_region', $regionId)->count();
    
    echo "\n{$region->nom_region}: {$count} contenus\n";
    
    // Vérifier quels types de contenus existent pour cette région
    $contenusParType = [];
    foreach ($types as $type) {
        $typeId = $type->id_type_contenu ?? $type->id;
        $typeCount = Contenu::where('id_region', $regionId)
            ->where('id_type_contenu', $typeId)
            ->count();
        
        if ($typeCount > 0) {
            $contenusParType[] = "  ✓ {$type->nom_contenu}: {$typeCount}";
        } else {
            $contenusParType[] = "  ✗ {$type->nom_contenu}: 0";
        }
    }
    
    foreach ($contenusParType as $line) {
        echo $line . "\n";
    }
}





















