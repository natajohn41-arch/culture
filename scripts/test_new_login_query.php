<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Utilisateur;

echo "=== Test de la nouvelle requête de connexion ===\n\n";

$testEmails = [
    'jnata313@gmail.com',
    'JNATA313@GMAIL.COM',
    '  jnata313@gmail.com  ',
    'jnata313@GMAIL.com',
];

foreach ($testEmails as $testEmail) {
    echo "Test avec: [{$testEmail}]\n";
    
    // Ancienne méthode
    $email = trim($testEmail);
    $userOld = Utilisateur::where('email', $email)->first();
    echo "  Ancienne méthode: " . ($userOld ? "✅ Trouvé ({$userOld->email})" : "❌ Non trouvé") . "\n";
    
    // Nouvelle méthode (case-insensitive)
    $emailNormalized = strtolower(trim($testEmail));
    $userNew = Utilisateur::whereRaw('LOWER(email) = ?', [$emailNormalized])->first();
    echo "  Nouvelle méthode: " . ($userNew ? "✅ Trouvé ({$userNew->email})" : "❌ Non trouvé") . "\n";
    
    echo "\n";
}





















