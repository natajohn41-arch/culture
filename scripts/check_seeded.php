<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

echo "=== Users created (emails like %example.test) ===\n";
$users = \App\Models\Utilisateur::where('email','like','%@example.test')->get();
if ($users->isEmpty()) {
    echo "No seeded users found.\n";
} else {
    foreach ($users as $u) {
        echo sprintf("%d - %s %s <=> %s (role_id=%s)\n", $u->id_utilisateur, $u->prenom, $u->nom, $u->email, $u->id_role);
    }
}

echo "\n=== Attempt login for first seeded user ===\n";
if ($users->isEmpty()) {
    echo "No user to test login.\n";
} else {
    $test = $users->first();
    $cred = ['email' => $test->email, 'password' => 'password'];
    $attempt = Auth::attempt($cred);
    echo 'Auth::attempt returned: ' . ($attempt ? 'true' : 'false') . PHP_EOL;
    if ($attempt) {
        echo 'Authenticated user id: ' . Auth::id() . PHP_EOL;
        Auth::logout();
    } else {
        // try direct hash check
        $ok = Hash::check('password', $test->mot_de_passe);
        echo 'Direct Hash::check on stored hash: ' . ($ok ? 'true' : 'false') . PHP_EOL;
    }
}

echo "\n=== Some contents ===\n";
$contenus = \App\Models\Contenu::limit(5)->get();
if ($contenus->isEmpty()) {
    echo "No contenus found.\n";
} else {
    foreach ($contenus as $c) {
        echo sprintf("%d - %s (statut=%s)\n", $c->id_contenu, $c->titre, $c->statut);
    }
}

echo "\n=== Route check: contenus.public ===\n";
try {
    $url = URL::route('contenus.public');
    echo "Route exists. URL: $url\n";
} catch (Exception $e) {
    echo "Route 'contenus.public' not found: " . $e->getMessage() . PHP_EOL;
}

echo "\nDone.\n";
