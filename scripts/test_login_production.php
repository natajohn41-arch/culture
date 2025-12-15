<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== Test de Connexion ===\n\n";

// Test avec tous les utilisateurs
$users = Utilisateur::all();
$password = 'Enaem123';

echo "Mot de passe de test: {$password}\n\n";

foreach ($users as $user) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Email: {$user->email}\n";
    echo "Nom: {$user->nom}\n";
    echo "Statut: {$user->statut}\n";
    
    // Vérifier le statut
    $statut = trim(strtolower($user->statut));
    if ($statut !== 'actif') {
        echo "❌ Statut non actif: [{$user->statut}]\n";
        continue;
    }
    
    // Vérifier le mot de passe
    if (empty($user->mot_de_passe)) {
        echo "❌ Mot de passe vide\n";
        continue;
    }
    
    // Tester le hash
    $passwordValid = Hash::check($password, $user->mot_de_passe);
    
    if (!$passwordValid) {
        echo "❌ Mot de passe invalide\n";
        echo "   Hash prefix: " . substr($user->mot_de_passe, 0, 20) . "...\n";
        continue;
    }
    
    echo "✅ Mot de passe valide\n";
    
    // Tester la connexion avec Auth
    try {
        // Simuler une requête de connexion
        $email = strtolower(trim($user->email));
        
        // Recherche de l'utilisateur (comme dans AuthController)
        $foundUser = Utilisateur::where('email', $email)->first();
        
        if (!$foundUser) {
            $foundUser = Utilisateur::whereRaw('LOWER(TRIM(email)) = ?', [strtolower(trim($email))])->first();
        }
        
        if (!$foundUser) {
            echo "❌ Utilisateur non trouvé avec la recherche\n";
            continue;
        }
        
        echo "✅ Utilisateur trouvé avec la recherche\n";
        
        // Tester Auth::login
        Auth::login($foundUser);
        
        if (Auth::check()) {
            echo "✅ Connexion Auth réussie\n";
            echo "   ID utilisateur connecté: " . Auth::id() . "\n";
            Auth::logout();
        } else {
            echo "❌ Connexion Auth échouée\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
        echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
    
    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n=== Test de la route de connexion ===\n";

// Vérifier que les routes existent
try {
    $loginRoute = route('login');
    echo "✅ Route login: {$loginRoute}\n";
    
    $loginPostRoute = route('login.post');
    echo "✅ Route login.post: {$loginPostRoute}\n";
} catch (\Exception $e) {
    echo "❌ Erreur route: " . $e->getMessage() . "\n";
}

// Vérifier le middleware
echo "\n=== Vérification du middleware ===\n";
$middlewarePath = app_path('Http/Middleware/RedirectIfAuthenticated.php');
if (file_exists($middlewarePath)) {
    echo "✅ Middleware RedirectIfAuthenticated existe\n";
} else {
    echo "❌ Middleware RedirectIfAuthenticated manquant\n";
}

echo "\n=== Test terminé ===\n";















