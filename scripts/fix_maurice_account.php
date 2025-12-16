<?php
/**
 * Script pour vérifier et corriger le compte de Maurice
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Vérification et Correction du Compte Maurice              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$email = 'mauricecomlan@uac.bj';
$password = 'Eneam123';

echo "🔍 Recherche de l'utilisateur: {$email}\n\n";

// Rechercher l'utilisateur avec différentes méthodes
$user = Utilisateur::where('email', $email)->first();

if (!$user) {
    // Essayer avec case-insensitive
    $user = Utilisateur::whereRaw('LOWER(TRIM(email)) = ?', [strtolower(trim($email))])->first();
}

if (!$user) {
    echo "❌ Utilisateur non trouvé dans la base de données.\n";
    echo "\n📋 Liste des utilisateurs existants:\n";
    $allUsers = Utilisateur::select('email', 'nom', 'prenom', 'statut')->get();
    foreach ($allUsers as $u) {
        echo "   - {$u->email} ({$u->prenom} {$u->nom}) - Statut: {$u->statut}\n";
    }
    echo "\n💡 Voulez-vous créer ce compte ? (modifiez le script pour créer)\n";
    exit(1);
}

echo "✅ Utilisateur trouvé !\n";
echo "   ID: {$user->id_utilisateur}\n";
echo "   Nom: {$user->nom}\n";
echo "   Prénom: {$user->prenom}\n";
echo "   Email: [{$user->email}]\n";
echo "   Statut actuel: [{$user->statut}]\n";
echo "   Longueur du statut: " . strlen($user->statut) . "\n";

// Vérifier le statut
$statutNormalise = trim(strtolower($user->statut));
echo "   Statut normalisé: [{$statutNormalise}]\n";

if ($statutNormalise !== 'actif') {
    echo "\n⚠️  Le compte n'est pas actif. Activation en cours...\n";
    $user->statut = 'actif';
    $user->save();
    echo "✅ Compte activé avec succès !\n";
} else {
    echo "✅ Le compte est déjà actif.\n";
}

// Vérifier le mot de passe
echo "\n🔐 Vérification du mot de passe...\n";
if (empty($user->mot_de_passe)) {
    echo "⚠️  Aucun mot de passe défini. Définition du mot de passe...\n";
    $user->mot_de_passe = Hash::make($password);
    $user->save();
    echo "✅ Mot de passe défini avec succès !\n";
} else {
    echo "   Hash du mot de passe existe (longueur: " . strlen($user->mot_de_passe) . ")\n";
    $passwordValid = Hash::check($password, $user->mot_de_passe);
    
    if ($passwordValid) {
        echo "✅ Le mot de passe est correct !\n";
    } else {
        echo "⚠️  Le mot de passe ne correspond pas. Réinitialisation...\n";
        $user->mot_de_passe = Hash::make($password);
        $user->save();
        echo "✅ Mot de passe réinitialisé avec succès !\n";
    }
}

// Vérifier le rôle
echo "\n👤 Vérification du rôle...\n";
$user->load('role');
if ($user->role) {
    echo "   Rôle: {$user->role->nom_role}\n";
} else {
    echo "⚠️  Aucun rôle assigné. Attribution du rôle Utilisateur par défaut...\n";
    $roleUtilisateur = \App\Models\Role::where('nom_role', 'Utilisateur')->first();
    if ($roleUtilisateur) {
        $user->id_role = $roleUtilisateur->id;
        $user->save();
        echo "✅ Rôle Utilisateur assigné !\n";
    }
}

// Test final de connexion
echo "\n🧪 Test de connexion simulé...\n";
$emailTest = strtolower(trim($email));
$userTest = Utilisateur::where('email', $emailTest)->first();

if (!$userTest) {
    echo "❌ ERREUR: Utilisateur non trouvé après normalisation\n";
    exit(1);
}

$statutTest = trim(strtolower($userTest->statut));
if ($statutTest !== 'actif') {
    echo "❌ ERREUR: Statut n'est pas 'actif': [{$statutTest}]\n";
    exit(1);
}

$passwordTest = Hash::check($password, $userTest->mot_de_passe);
if (!$passwordTest) {
    echo "❌ ERREUR: Mot de passe invalide\n";
    exit(1);
}

echo "✅ Tous les tests passent !\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Résumé:\n";
echo "   - Email: {$email}\n";
echo "   - Mot de passe: {$password}\n";
echo "   - Statut: actif\n";
echo "   - Compte prêt pour la connexion\n";
echo "\n✅ Le compte est maintenant configuré correctement !\n";
echo "🌐 Vous pouvez maintenant vous connecter avec ces identifiants.\n";







