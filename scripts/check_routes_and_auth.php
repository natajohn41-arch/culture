<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

echo "Routes contenant 'contenus':\n";
foreach (Route::getRoutes() as $route) {
    $name = $route->getName();
    $uri = $route->uri();
    if (strpos($name ?? '', 'contenus') !== false || strpos($uri, 'contenus') !== false) {
        printf("- %-40s name=%s\n", $uri, $name ?? '(null)');
    }
}

echo "\nRoute nommée 'contenus.public' existe? ";
var_export(Route::has('contenus.public'));
echo "\n\n";

echo "Test Auth::attempt pour admin@example.test: ";
try {
    $res = Auth::attempt(['email' => 'admin@example.test', 'password' => 'password']);
    var_export($res);
} catch (Throwable $e) {
    echo "Erreur Auth::attempt: " . $e->getMessage();
}
echo "\n";

$u = Utilisateur::where('email', 'admin@example.test')->first();
if ($u) {
    echo "Hash::check('password', \$u->mot_de_passe) => ";
    var_export(Hash::check('password', $u->mot_de_passe));
    echo "\n";
} else {
    echo "Utilisateur admin@example.test non trouvé en base.\n";
}

echo "Terminé.\n";
