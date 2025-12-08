<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$pdo = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM utilisateurs");
foreach ($pdo as $col) {
    echo $col->Field . ' | ' . $col->Type . ' | ' . $col->Null . ' | ' . $col->Default . PHP_EOL;
}
