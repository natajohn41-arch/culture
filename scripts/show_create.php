<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$rows = \Illuminate\Support\Facades\DB::select('SHOW CREATE TABLE utilisateurs');
print_r($rows);
