<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\DB;

echo "=== Vérification et correction des statuts utilisateurs ===\n\n";

$users = Utilisateur::all();
$fixed = 0;

foreach ($users as $user) {
    $originalStatut = $user->statut;
    $normalizedStatut = trim(strtolower($originalStatut));
    
    echo "Email: {$user->email}\n";
    echo "  Statut original: [{$originalStatut}] (longueur: " . strlen($originalStatut) . ")\n";
    echo "  Statut normalisé: [{$normalizedStatut}]\n";
    
    // Si le statut n'est pas exactement 'actif', le corriger
    if ($normalizedStatut !== 'actif') {
        echo "  ⚠️  Statut incorrect, correction en cours...\n";
        $user->statut = 'actif';
        $user->save();
        $fixed++;
        echo "  ✅ Statut corrigé à 'actif'\n";
    } else {
        // Même si normalisé c'est 'actif', s'assurer que c'est exactement 'actif' sans espaces
        if ($originalStatut !== 'actif') {
            echo "  ⚠️  Statut a des espaces, correction en cours...\n";
            $user->statut = 'actif';
            $user->save();
            $fixed++;
            echo "  ✅ Statut corrigé à 'actif'\n";
        } else {
            echo "  ✅ Statut correct\n";
        }
    }
    
    // Vérifier aussi l'email pour s'assurer qu'il n'y a pas d'espaces
    $emailOriginal = $user->email;
    $emailNormalized = trim(strtolower($emailOriginal));
    if ($emailOriginal !== $emailNormalized) {
        echo "  ⚠️  Email a des problèmes, correction en cours...\n";
        $user->email = $emailNormalized;
        $user->save();
        echo "  ✅ Email corrigé\n";
    }
    
    echo "\n";
}

echo "=== Résumé ===\n";
echo "Total utilisateurs: " . $users->count() . "\n";
echo "Utilisateurs corrigés: {$fixed}\n";








