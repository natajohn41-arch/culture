<?php
/**
 * Script pour réinitialiser les mots de passe des utilisateurs de test
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Réinitialisation des Mots de Passe                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Liste des utilisateurs à mettre à jour
$usersToUpdate = [
    'admin@example.test' => 'password',
    'moderateur@example.test' => 'password',
    'auteur@example.test' => 'password',
    'utilisateur@example.test' => 'password',
];

// Ajouter l'admin de production si configuré
$adminEmail = env('ADMIN_EMAIL', 'admin@culture.bj');
$adminPassword = env('ADMIN_PASSWORD', 'ChangeMe123!');
if ($adminEmail && $adminPassword) {
    $usersToUpdate[$adminEmail] = $adminPassword;
}

$updated = 0;
$notFound = 0;

foreach ($usersToUpdate as $email => $password) {
    $user = Utilisateur::where('email', $email)->first();
    
    if (!$user) {
        echo "⚠️  Utilisateur non trouvé: {$email}\n";
        $notFound++;
        continue;
    }
    
    // Mettre à jour le mot de passe
    $user->mot_de_passe = Hash::make($password);
    $user->save();
    
    echo "✅ Mot de passe mis à jour pour: {$email} (Rôle: " . ($user->role ? $user->role->nom_role : 'N/A') . ")\n";
    $updated++;
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Résumé:\n";
echo "   - ✅ {$updated} utilisateur(s) mis à jour\n";
if ($notFound > 0) {
    echo "   - ⚠️  {$notFound} utilisateur(s) non trouvé(s)\n";
}
echo "\n🔑 Mots de passe par défaut:\n";
echo "   - admin@example.test: password\n";
echo "   - moderateur@example.test: password\n";
echo "   - auteur@example.test: password\n";
echo "   - utilisateur@example.test: password\n";
if (isset($adminEmail) && $adminEmail !== 'admin@culture.bj') {
    echo "   - {$adminEmail}: {$adminPassword}\n";
}
echo "\n✅ Terminé !\n";

