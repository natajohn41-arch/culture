<?php
/**
 * Script pour tester la connexion et les rôles des utilisateurs
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Test des Utilisateurs et Rôles                            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Vérifier les rôles
echo "📋 Rôles disponibles:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "  - ID: {$role->id}, Nom: {$role->nom_role}\n";
}
echo "\n";

// Tester chaque rôle
$testEmails = [
    'admin@example.test',
    'moderateur@example.test',
    'auteur@example.test',
    'utilisateur@example.test',
];

foreach ($testEmails as $email) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔍 Test pour: {$email}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $user = Utilisateur::where('email', $email)->first();
    
    if (!$user) {
        echo "❌ Utilisateur non trouvé\n\n";
        continue;
    }
    
    echo "✅ Utilisateur trouvé:\n";
    echo "   - ID: {$user->id_utilisateur}\n";
    echo "   - Nom: {$user->nom} {$user->prenom}\n";
    echo "   - Email: {$user->email}\n";
    echo "   - Statut: {$user->statut}\n";
    echo "   - ID Role: {$user->id_role}\n";
    
    // Charger la relation role
    $user->load('role');
    
    if ($user->role) {
        echo "   - Role chargé: {$user->role->nom_role}\n";
    } else {
        echo "   - ❌ Role NON chargé (id_role = {$user->id_role})\n";
        // Essayer de trouver le rôle directement
        $role = Role::find($user->id_role);
        if ($role) {
            echo "   - Role trouvé directement: {$role->nom_role}\n";
        } else {
            echo "   - ❌ Role avec ID {$user->id_role} n'existe pas!\n";
        }
    }
    
    // Tester les méthodes
    echo "\n🔐 Tests des méthodes:\n";
    echo "   - isAdmin(): " . ($user->isAdmin() ? '✅ true' : '❌ false') . "\n";
    echo "   - isModerator(): " . ($user->isModerator() ? '✅ true' : '❌ false') . "\n";
    echo "   - isAuthor(): " . ($user->isAuthor() ? '✅ true' : '❌ false') . "\n";
    
    // Tester le mot de passe
    $password = 'password';
    $passwordValid = Hash::check($password, $user->mot_de_passe);
    echo "   - Mot de passe 'password' valide: " . ($passwordValid ? '✅ Oui' : '❌ Non') . "\n";
    
    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Test terminé\n";

















