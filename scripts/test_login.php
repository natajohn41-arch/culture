<?php
/**
 * Script pour tester la connexion et diagnostiquer les problèmes d'authentification
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

echo "=== Diagnostic de connexion ===\n\n";

// Lister tous les utilisateurs
echo "📋 Utilisateurs dans la base de données:\n";
$users = Utilisateur::all();
if ($users->isEmpty()) {
    echo "❌ Aucun utilisateur trouvé dans la base de données.\n";
    echo "💡 Exécutez: php artisan db:seed --class=UsersPerRoleSeeder\n";
    exit(1);
}

foreach ($users as $user) {
    echo sprintf(
        "  - ID: %d | Email: %s | Statut: %s | Role ID: %s\n",
        $user->id_utilisateur ?? $user->id,
        $user->email,
        $user->statut,
        $user->id_role
    );
    echo sprintf(
        "    Mot de passe hashé: %s\n",
        !empty($user->mot_de_passe) ? substr($user->mot_de_passe, 0, 20) . '...' : 'VIDE'
    );
}

echo "\n=== Test de connexion avec 'password' ===\n";
foreach ($users as $user) {
    if (empty($user->mot_de_passe)) {
        echo "⚠️  {$user->email}: Mot de passe vide\n";
        continue;
    }
    
    $testPassword = 'password';
    $check = Hash::check($testPassword, $user->mot_de_passe);
    echo sprintf(
        "  %s | Email: %s | Hash::check('password', hash) = %s\n",
        $check ? '✅' : '❌',
        $user->email,
        $check ? 'TRUE' : 'FALSE'
    );
    
    if (!$check) {
        echo "    ⚠️  Le mot de passe 'password' ne correspond pas pour cet utilisateur.\n";
        echo "    💡 Essayez de réinitialiser le mot de passe avec:\n";
        echo "       php artisan tinker\n";
        echo "       \$user = App\\Models\\Utilisateur::where('email', '{$user->email}')->first();\n";
        echo "       \$user->mot_de_passe = Hash::make('password');\n";
        echo "       \$user->save();\n";
    }
}

echo "\n=== Test avec différents mots de passe courants ===\n";
$commonPasswords = ['password', 'Password', 'PASSWORD', '123456', 'admin', 'Admin'];
foreach ($users->take(1) as $user) {
    if (empty($user->mot_de_passe)) {
        continue;
    }
    echo "Test pour {$user->email}:\n";
    foreach ($commonPasswords as $pwd) {
        $check = Hash::check($pwd, $user->mot_de_passe);
        if ($check) {
            echo "  ✅ '{$pwd}' correspond !\n";
            break;
        }
    }
}

echo "\n=== Recommandations ===\n";
echo "1. Si aucun utilisateur n'a de mot de passe valide, réinitialisez-les:\n";
echo "   php artisan tinker\n";
echo "   App\\Models\\Utilisateur::all()->each(function(\$u) { \$u->mot_de_passe = Hash::make('password'); \$u->save(); });\n";
echo "\n2. Pour créer un nouvel utilisateur admin:\n";
echo "   php artisan tinker\n";
echo "   \$user = App\\Models\\Utilisateur::create([...]);\n";

echo "\n✅ Diagnostic terminé.\n";

