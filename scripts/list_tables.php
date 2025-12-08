<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$rows = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($rows as $r) {
    foreach ($r as $v) echo $v . PHP_EOL;
}
