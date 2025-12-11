<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;

echo "=== LISTE DES COMPTES UTILISATEURS ===\n\n";

$users = Utilisateur::with('role')->orderBy('email')->get();

if ($users->isEmpty()) {
    echo "❌ Aucun utilisateur trouvé.\n";
    exit(1);
}

echo sprintf("%-40s | %-25s | %-15s | %-10s\n", "EMAIL", "NOM COMPLET", "RÔLE", "STATUT");
echo str_repeat("-", 95) . "\n";

foreach ($users as $user) {
    $nomComplet = trim(($user->prenom ?? '') . ' ' . ($user->nom ?? ''));
    $role = $user->role ? $user->role->nom_role : 'N/A';
    
    echo sprintf(
        "%-40s | %-25s | %-15s | %-10s\n",
        $user->email,
        $nomComplet ?: 'N/A',
        $role,
        $user->statut ?? 'N/A'
    );
}

echo "\n=== INFORMATIONS DE CONNEXION ===\n";
echo "⚠️  Les mots de passe sont hashés dans la base de données.\n";
echo "💡 Pour réinitialiser un mot de passe, utilisez:\n";
echo "   php artisan users:reset-passwords --email=VOTRE_EMAIL --password=VOTRE_MOT_DE_PASSE\n\n";

echo "📋 Comptes de test (créés par UsersPerRoleSeeder):\n";
$testUsers = $users->filter(function($u) {
    return str_contains($u->email, '@example.test');
});

if ($testUsers->isNotEmpty()) {
    echo "   Ces comptes utilisent généralement le mot de passe: 'password'\n";
    foreach ($testUsers as $user) {
        echo "   - {$user->email} (Rôle: " . ($user->role ? $user->role->nom_role : 'N/A') . ")\n";
    }
}

echo "\n✅ Total: {$users->count()} utilisateur(s)\n";





