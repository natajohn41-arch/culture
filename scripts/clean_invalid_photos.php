<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Storage;

echo "=== Nettoyage des photos invalides ===\n\n";

$users = Utilisateur::whereNotNull('photo')->get();
$cleaned = 0;

foreach ($users as $user) {
    if ($user->photo === 'default.png' || empty($user->photo)) {
        echo "Nettoyage de {$user->email}: photo = '{$user->photo}'\n";
        $user->photo = null;
        $user->save();
        $cleaned++;
    } elseif (!Storage::disk('public')->exists($user->photo)) {
        echo "Photo introuvable pour {$user->email}: {$user->photo}\n";
        $user->photo = null;
        $user->save();
        $cleaned++;
    }
}

echo "\n=== Résumé ===\n";
echo "Photos nettoyées: {$cleaned}\n";

