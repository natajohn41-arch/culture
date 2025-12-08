<?php
// Apply limited, safe route-name replacements in resources/views
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$viewsDir = __DIR__ . '/../resources/views';
$backupDir = __DIR__ . '/views_backup_before_fix_' . date('Ymd_His');
@mkdir($backupDir, 0755, true);

$mappings = [
    // wrong => correct
    'contenu' => 'contenus',
    'contenu.' => 'contenus.',
    'contenu_' => 'contenus_',
    'media' => 'medias',
    'media.' => 'medias.',
    'typecontenu' => 'type-contenus',
    'typecontenu.' => 'type-contenus.',
    'typecontenu_' => 'type-contenus_',
    'typemedia' => 'type-medias',
    'typemedia.' => 'type-medias.',
    'mes-contenus' => 'mes.contenus',
    'mes-contenus.' => 'mes.contenus.',
    'contenu.store' => 'contenus.store',
    'contenu.update' => 'contenus.update',
    'contenu.show' => 'contenus.show',
    'media.index' => 'medias.index',
    'media.store' => 'medias.store',
    'media.update' => 'medias.update',
];

$pattern = '/route\s*\(\s*(["\'])([a-zA-Z0-9_\.\-]+)\1/';
$changedFiles = [];

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
foreach ($it as $f) {
    if (!$f->isFile()) continue;
    $ext = pathinfo($f->getFilename(), PATHINFO_EXTENSION);
    if (! in_array($ext, ['php', 'blade.php'])) continue;
    $path = $f->getPathname();
    $content = file_get_contents($path);
    if (!preg_match_all($pattern, $content, $m)) continue;
    $orig = $content;
    foreach ($m[2] as $name) {
        foreach ($mappings as $wrong => $right) {
            // only replace whole token or token with dot
            if (strpos($name, $wrong) !== false) {
                $newName = str_replace($wrong, $right, $name);
                if ($newName === $name) continue;
                // verify route exists
                if (Illuminate\Support\Facades\Route::has($newName)) {
                    // replace occurrences of the exact name inside route('...') only
                    $content = preg_replace("/route\s*\(\s*(['\"])" . preg_quote($name, '/') . "\1/", "route(\1" . $newName . "\1", $content);
                }
            }
        }
    }
    if ($content !== $orig) {
        // backup once
        $rel = substr($path, strlen(__DIR__ . '/../'));
        $bpath = $backupDir . '/' . $rel;
        @mkdir(dirname($bpath), 0755, true);
        if (!file_exists($bpath)) file_put_contents($bpath, $orig);
        file_put_contents($path, $content);
        $changedFiles[] = $path;
    }
}

echo "Backup saved to: $backupDir\n";
echo "Changed files: " . count($changedFiles) . "\n";
foreach ($changedFiles as $cf) echo " - $cf\n";

// run scanner to show remaining missing names
passthru('php ' . escapeshellarg(__DIR__ . '/find_missing_routes.php'));
