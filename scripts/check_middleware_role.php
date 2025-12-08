<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
try {
    $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "App and kernel bootstrapped\n";
    try {
        $m = $app->make('role');
        echo "Resolved 'role' to: " . (is_object($m) ? get_class($m) : print_r($m, true)) . "\n";
    } catch (Throwable $e) {
        echo "Error resolving 'role': " . get_class($e) . " - " . $e->getMessage() . "\n";
    }
} catch (Throwable $e) {
    echo "Bootstrap error: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
