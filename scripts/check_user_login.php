<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

$email = 'jnata313@gmail.com';

echo "=== Vérification du compte: {$email} ===\n\n";

$user = Utilisateur::where('email', $email)->first();

if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit(1);
}

echo "✅ Utilisateur trouvé:\n";
echo "   ID: {$user->id_utilisateur}\n";
echo "   Nom: {$user->nom}\n";
echo "   Prénom: {$user->prenom}\n";
echo "   Email: [{$user->email}]\n";
echo "   Statut: [{$user->statut}]\n";
echo "   Longueur du statut: " . strlen($user->statut) . "\n";
echo "   Statut === 'actif': " . ($user->statut === 'actif' ? 'true' : 'false') . "\n";
echo "   Statut == 'actif': " . ($user->statut == 'actif' ? 'true' : 'false') . "\n";
echo "   trim(statut) === 'actif': " . (trim($user->statut) === 'actif' ? 'true' : 'false') . "\n";
echo "   Mot de passe hashé existe: " . (!empty($user->mot_de_passe) ? 'Oui' : 'Non') . "\n";

if (!empty($user->mot_de_passe)) {
    echo "   Longueur du hash: " . strlen($user->mot_de_passe) . "\n";
    echo "   Préfixe du hash: " . substr($user->mot_de_passe, 0, 10) . "...\n";
    
    // Tester le mot de passe
    $testPassword = 'Enaem123';
    $passwordValid = Hash::check($testPassword, $user->mot_de_passe);
    echo "   Test mot de passe 'Enaem123': " . ($passwordValid ? '✅ Valide' : '❌ Invalide') . "\n";
}

echo "\n=== Test de connexion simulé ===\n";
$emailTrimmed = trim($email);
$userFound = Utilisateur::where('email', $emailTrimmed)->first();

if (!$userFound) {
    echo "❌ Utilisateur non trouvé après trim\n";
} else {
    echo "✅ Utilisateur trouvé après trim\n";
    if ($userFound->statut !== 'actif') {
        echo "❌ Statut n'est pas 'actif': [{$userFound->statut}]\n";
    } else {
        echo "✅ Statut est 'actif'\n";
    }
}








