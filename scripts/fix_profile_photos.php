<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Storage;

echo "=== Vérification et correction des photos de profil ===\n\n";

$users = Utilisateur::all();
$fixed = 0;
$notFound = 0;

foreach ($users as $user) {
    echo "Utilisateur: {$user->email}\n";
    
    if (empty($user->photo)) {
        echo "  ⚠️  Aucune photo définie\n\n";
        continue;
    }
    
    echo "  Photo actuelle: {$user->photo}\n";
    
    // Vérifier si le fichier existe
    $photoPath = $user->photo;
    
    // Si le chemin commence par "photos/" ou "utilisateurs/", c'est correct
    // Sinon, essayer de trouver le fichier dans différents emplacements
    $possiblePaths = [
        $photoPath,
        'photos/' . basename($photoPath),
        'photos/utilisateurs/' . basename($photoPath),
        'utilisateurs/' . basename($photoPath),
    ];
    
    $found = false;
    $correctPath = null;
    
    foreach ($possiblePaths as $path) {
        if (Storage::disk('public')->exists($path)) {
            $found = true;
            $correctPath = $path;
            echo "  ✅ Fichier trouvé: {$path}\n";
            break;
        }
    }
    
    if (!$found) {
        echo "  ❌ Fichier non trouvé\n";
        $notFound++;
    } else {
        // Si le chemin est différent, le corriger
        if ($correctPath !== $photoPath) {
            echo "  🔧 Correction du chemin: {$photoPath} -> {$correctPath}\n";
            $user->photo = $correctPath;
            $user->save();
            $fixed++;
        } else {
            echo "  ✅ Chemin correct\n";
        }
    }
    
    echo "\n";
}

echo "=== Résumé ===\n";
echo "Total utilisateurs: " . $users->count() . "\n";
echo "Photos corrigées: {$fixed}\n";
echo "Photos non trouvées: {$notFound}\n";

// Vérifier le lien symbolique
echo "\n=== Vérification du lien symbolique ===\n";
$linkPath = public_path('storage');
$targetPath = storage_path('app/public');

if (is_link($linkPath)) {
    $linkTarget = readlink($linkPath);
    echo "✅ Lien symbolique existe: {$linkPath} -> {$linkTarget}\n";
    if ($linkTarget === $targetPath) {
        echo "✅ Le lien pointe vers le bon répertoire\n";
    } else {
        echo "⚠️  Le lien ne pointe pas vers le bon répertoire\n";
    }
} else {
    echo "❌ Lien symbolique manquant\n";
    echo "   Exécutez: php artisan storage:link\n";
}













