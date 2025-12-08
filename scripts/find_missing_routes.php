<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

$paths = [
    __DIR__ . '/../resources/views',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../resources/views_backup_20251125_091452',
];

$pattern = '/route\s*\(\s*["\']([a-zA-Z0-9_\.\-]+)["\']/';
$found = [];

foreach ($paths as $p) {
    if (!is_dir($p)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($p));
    foreach ($it as $f) {
        if (!$f->isFile()) continue;
        $ext = pathinfo($f->getFilename(), PATHINFO_EXTENSION);
        if (!in_array($ext, ['php', 'blade.php'])) continue;
        $content = file_get_contents($f->getPathname());
        if (preg_match_all($pattern, $content, $m)) {
            foreach ($m[1] as $name) {
                $found[$name][] = $f->getPathname();
            }
        }
    }
}

ksort($found);

$defined = [];
foreach (Route::getRoutes() as $r) {
    $n = $r->getName();
    if ($n) $defined[$n] = true;
}

echo "Routes référencées dans les vues (extrait) :\n";
foreach ($found as $name => $files) {
    $status = isset($defined[$name]) ? 'DEFINED' : 'MISSING';
    echo sprintf("%s : %s\n", str_pad($status, 8), $name);
    if ($status === 'MISSING') {
        foreach ($files as $f) {
            echo "  - $f\n";
        }
    }
}

echo "\nRésumé: \n";
$missing = array_filter(array_keys($found), function($n) use ($defined){ return !isset($defined[$n]); });
echo "Total référencés: " . count($found) . "\n";
echo "Total manquants: " . count($missing) . "\n";

if (count($missing) === 0) {
    echo "Aucun nom de route manquant détecté.\n";
} else {
    echo "Noms manquants listés ci‑dessus.\n";
}
