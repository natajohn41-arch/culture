<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

$viewsDir = __DIR__ . '/../resources/views';
$backupDir = __DIR__ . '/../resources/views_auto_fix_backup_' . date('Ymd_His');
mkdir($backupDir, 0755, true);

// collect defined routes
$defined = [];
foreach (Route::getRoutes() as $r) {
    $n = $r->getName();
    if ($n) $defined[$n] = true;
}

// find usages
$pattern = '/route\s*\(\s*["\']([a-zA-Z0-9_\.\-]+)["\']/';
$found = [];
$files = [];

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
foreach ($it as $f) {
    if (!$f->isFile()) continue;
    $ext = pathinfo($f->getFilename(), PATHINFO_EXTENSION);
    if (!in_array($ext, ['php', 'blade.php'])) continue;
    $content = file_get_contents($f->getPathname());
    if (preg_match_all($pattern, $content, $m)) {
        foreach ($m[1] as $name) {
            $found[$name][] = $f->getPathname();
            $files[$f->getPathname()] = $content;
        }
    }
}

// determine missing
$missing = array_filter(array_keys($found), function($n) use ($defined){ return !isset($defined[$n]); });

echo "Found " . count($found) . " referenced route names, " . count($missing) . " missing.\n";

// heuristics
function tryCandidates($name) {
    $c = [];
    // 1) singular -> add s to base
    if (strpos($name, '.') !== false) {
        list($base, $rest) = explode('.', $name, 2);
    } else {
        $base = $name; $rest = null;
    }
    $c[] = ($rest ? ($base . 's.' . $rest) : ($base . 's'));
    // 2) underscore -> dash
    $c[] = str_replace('_', '-', $name);
    // 3) dash -> dot in prefix (mes-contenus -> mes.contenus)
    $c[] = str_replace('-', '.', $name);
    // 4) combine: underscore->dash then singularize/pluralize
    $c[] = str_replace('_', '-', ($rest ? ($base . 's.' . $rest) : ($base . 's')));
    // 5) try pluralizing by adding 's' to last token after dot
    if (strpos($name, '.') !== false) {
        $parts = explode('.', $name);
        $last = array_pop($parts);
        $parts[] = $last . 's';
        $c[] = implode('.', $parts);
    }
    // unique
    $c = array_values(array_unique($c));
    return $c;
}

$applied = [];

foreach ($missing as $name) {
    $cands = tryCandidates($name);
    $good = [];
    foreach ($cands as $cand) {
        if (Route::has($cand)) $good[] = $cand;
    }
    if (count($good) === 1) {
        $target = $good[0];
        echo "Auto-mapping: $name -> $target\n";
        // apply replacements in files where $name appears
        foreach ($found[$name] as $fp) {
            // backup file once
            $rel = substr($fp, strlen(__DIR__ . '/../'));
            $bpath = $backupDir . '/' . $rel;
            @mkdir(dirname($bpath), 0755, true);
            if (!file_exists($bpath)) file_put_contents($bpath, file_get_contents($fp));
            $content = file_get_contents($fp);
            // replace occurrences of route('name' and "name"
            $patternFull = '/route\s*\(\s*(["\"])' . preg_quote($name, '/') . '\1/';
            $replacement = 'route(\1' . $target . '\1';
            $new = preg_replace($patternFull, $replacement, $content);
            if ($new !== null && $new !== $content) {
                file_put_contents($fp, $new);
                $applied[$name] = $target;
            }
        }
    } else {
        echo "No unique mapping for $name (candidates: " . implode(', ', $good) . ")\n";
    }
}

echo "\nApplied mappings:\n";
foreach ($applied as $k=>$v) echo "$k -> $v\n";

echo "Backup saved to $backupDir\n";

// final re-check
echo "\nRe-running check...\n";
passthru('php ' . escapeshellarg(__DIR__ . '/find_missing_routes.php'));
