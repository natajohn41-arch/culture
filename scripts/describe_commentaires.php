<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach(\Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM commentaires') as $c) {
    echo $c->Field.'|'.$c->Type.'|'.$c->Null.'|'.$c->Default.PHP_EOL;
}
