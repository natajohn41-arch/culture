<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== Test de connexion pour tous les utilisateurs ===\n\n";

$users = Utilisateur::all();
$password = 'Enaem123';

foreach ($users as $user) {
    echo "Email: {$user->email}\n";
    echo "  Statut: {$user->statut}\n";
    
    // Simuler exactement ce que fait AuthController
    $email = trim($user->email);
    $userFound = Utilisateur::where('email', $email)->first();
    
    if (!$userFound) {
        echo "  ❌ ERREUR: Utilisateur non trouvé après trim\n";
        continue;
    }
    
    if ($userFound->statut !== 'actif') {
        echo "  ❌ ERREUR: Statut n'est pas 'actif': [{$userFound->statut}]\n";
        continue;
    }
    
    if (empty($userFound->mot_de_passe)) {
        echo "  ❌ ERREUR: Mot de passe vide\n";
        continue;
    }
    
    $passwordValid = Hash::check($password, $userFound->mot_de_passe);
    if (!$passwordValid) {
        echo "  ❌ ERREUR: Mot de passe invalide\n";
        continue;
    }
    
    echo "  ✅ Tous les checks passent\n";
    
    // Essayer de se connecter avec Auth
    try {
        Auth::login($userFound);
        if (Auth::check()) {
            echo "  ✅ Connexion Auth réussie\n";
            Auth::logout();
        } else {
            echo "  ❌ Connexion Auth échouée\n";
        }
    } catch (\Exception $e) {
        echo "  ❌ Exception: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}























