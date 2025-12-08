<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roles = \App\Models\Role::all();
if ($roles->isEmpty()) {
    echo "No roles found\n";
    exit(0);
}
foreach ($roles as $r) {
    echo $r->id_role . ' - ' . $r->nom_role . PHP_EOL;
}
