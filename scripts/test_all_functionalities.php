<?php
/**
 * Script pour tester toutes les fonctionnalités principales de l'application
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contenu;
use App\Models\Utilisateur;
use App\Models\Region;
use App\Models\Langue;
use App\Models\TypeContenu;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Test de Toutes les Fonctionnalités                         ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$erreurs = [];
$succes = [];

// Test 1: Vérifier la connexion à la base de données
echo "🔍 Test 1: Connexion à la base de données...\n";
try {
    DB::connection()->getPdo();
    echo "   ✅ Connexion réussie\n";
    $succes[] = "Connexion à la base de données";
} catch (\Exception $e) {
    echo "   ❌ Erreur de connexion: " . $e->getMessage() . "\n";
    $erreurs[] = "Connexion à la base de données: " . $e->getMessage();
}

// Test 2: Vérifier les modèles
echo "\n🔍 Test 2: Vérification des modèles...\n";
$modeles = [
    'Contenu' => Contenu::class,
    'Utilisateur' => Utilisateur::class,
    'Region' => Region::class,
    'Langue' => Langue::class,
    'TypeContenu' => TypeContenu::class,
    'Role' => Role::class,
];

foreach ($modeles as $nom => $classe) {
    try {
        $count = $classe::count();
        echo "   ✅ {$nom}: {$count} enregistrement(s)\n";
        $succes[] = "Modèle {$nom}";
    } catch (\Exception $e) {
        echo "   ❌ {$nom}: Erreur - " . $e->getMessage() . "\n";
        $erreurs[] = "Modèle {$nom}: " . $e->getMessage();
    }
}

// Test 3: Vérifier les relations
echo "\n🔍 Test 3: Vérification des relations...\n";
try {
    $contenu = Contenu::with(['region', 'langue', 'typeContenu', 'auteur'])->first();
    if ($contenu) {
        $relationsOk = true;
        if (!$contenu->region) $relationsOk = false;
        if (!$contenu->langue) $relationsOk = false;
        if (!$contenu->typeContenu) $relationsOk = false;
        
        if ($relationsOk) {
            echo "   ✅ Relations fonctionnelles\n";
            $succes[] = "Relations des modèles";
        } else {
            echo "   ⚠️  Certaines relations sont manquantes\n";
        }
    } else {
        echo "   ⚠️  Aucun contenu trouvé pour tester les relations\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Erreur lors du test des relations: " . $e->getMessage() . "\n";
    $erreurs[] = "Relations: " . $e->getMessage();
}

// Test 4: Vérifier les rôles
echo "\n🔍 Test 4: Vérification des rôles...\n";
try {
    $roles = Role::all();
    $rolesAttendus = ['Admin', 'Moderateur', 'Auteur', 'Utilisateur'];
    $rolesTrouves = $roles->pluck('nom_role')->toArray();
    
    foreach ($rolesAttendus as $role) {
        if (in_array($role, $rolesTrouves)) {
            echo "   ✅ Rôle {$role} existe\n";
        } else {
            echo "   ⚠️  Rôle {$role} manquant\n";
        }
    }
    $succes[] = "Vérification des rôles";
} catch (\Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    $erreurs[] = "Rôles: " . $e->getMessage();
}

// Test 5: Vérifier les utilisateurs par rôle
echo "\n🔍 Test 5: Vérification des utilisateurs par rôle...\n";
try {
    $roleAdmin = Role::where('nom_role', 'Admin')->first();
    $roleModerateur = Role::where('nom_role', 'Moderateur')->first();
    $roleAuteur = Role::where('nom_role', 'Auteur')->first();
    
    if ($roleAdmin) {
        $admins = Utilisateur::where('id_role', $roleAdmin->id)->count();
        echo "   ✅ Admins: {$admins}\n";
    }
    if ($roleModerateur) {
        $moderateurs = Utilisateur::where('id_role', $roleModerateur->id)->count();
        echo "   ✅ Modérateurs: {$moderateurs}\n";
    }
    if ($roleAuteur) {
        $auteurs = Utilisateur::where('id_role', $roleAuteur->id)->count();
        echo "   ✅ Auteurs: {$auteurs}\n";
    }
    $succes[] = "Utilisateurs par rôle";
} catch (\Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    $erreurs[] = "Utilisateurs par rôle: " . $e->getMessage();
}

// Test 6: Vérifier les contenus par statut
echo "\n🔍 Test 6: Vérification des contenus par statut...\n";
try {
    $contenusValides = Contenu::where('statut', 'valide')->count();
    $contenusEnAttente = Contenu::where('statut', 'en_attente')->count();
    $contenusRejetes = Contenu::where('statut', 'rejete')->count();
    
    echo "   ✅ Contenus valides: {$contenusValides}\n";
    echo "   ✅ Contenus en attente: {$contenusEnAttente}\n";
    echo "   ✅ Contenus rejetés: {$contenusRejetes}\n";
    $succes[] = "Contenus par statut";
} catch (\Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    $erreurs[] = "Contenus par statut: " . $e->getMessage();
}

// Résumé
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Résumé:\n";
echo "   - Tests réussis: " . count($succes) . "\n";
echo "   - Erreurs: " . count($erreurs) . "\n";

if (count($erreurs) > 0) {
    echo "\n❌ Erreurs trouvées:\n";
    foreach ($erreurs as $erreur) {
        echo "   - {$erreur}\n";
    }
    exit(1);
} else {
    echo "\n✅ Tous les tests sont passés avec succès !\n";
}






