<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LangueController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeMediaController;
use App\Http\Controllers\TypeContenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaiementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil publique
Route::get('/', [ContenuController::class, 'accueil'])->name('accueil');
Route::get('/contenus-public', [ContenuController::class, 'indexPublic'])->name('contenus.public');
Route::get('/a-propos', [PageController::class, 'about'])->name('about');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Routes Protégées (Authentification requise)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Commentaires (tous les utilisateurs authentifiés)
    Route::post('/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
    Route::delete('/commentaires/{id}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');

    // Route pour voir les contenus (accessible à tous les utilisateurs authentifiés)
    Route::get('/contenus/{id}', [ContenuController::class, 'show'])->name('contenus.show');
    Route::get('/medias', [MediaController::class, 'index'])->name('media.index');
    Route::get('medias/{id}/download', [MediaController::class, 'download'])
        ->name('medias.download');

    /*
    |--------------------------------------------------------------------------
    | Routes selon les Rôles
    |--------------------------------------------------------------------------
    */

    // Routes pour les Auteurs
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':Auteur'])->prefix('mes')->name('mes.')->group(function () {
        Route::get('/contenus', [ContenuController::class, 'mesContenus'])->name('contenus.index');
        Route::get('/contenus/create', [ContenuController::class, 'create'])->name('contenus.create');
        Route::post('/contenus', [ContenuController::class, 'store'])->name('contenus.store');
        Route::get('/contenus/{id}/edit', [ContenuController::class, 'edit'])->name('contenus.edit');
        Route::put('/contenus/{id}', [ContenuController::class, 'update'])->name('contenus.update');
        Route::delete('/contenus/{id}', [ContenuController::class, 'destroy'])->name('contenus.destroy');
        Route::get('/contenus/{id}', [ContenuController::class, 'show'])->name('contenus.show');
    });

    // Routes pour les Modérateurs
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':Moderateur'])->group(function () {
        Route::get('/contenus-a-valider', [ContenuController::class, 'aValider'])->name('contenus.a-valider');
        Route::post('/contenus/{id}/valider', [ContenuController::class, 'valider'])->name('contenus.valider');
        Route::post('/contenus/{id}/rejeter', [ContenuController::class, 'rejeter'])->name('contenus.rejeter');
    });

    // Routes pour les Administrateurs
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':Admin'])->group(function () {
        // Dashboard admin
        Route::get('/admin-dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    
        // Gestion des ressources
        Route::resource('utilisateurs', UtilisateurController::class);
        Route::post('utilisateurs/{id}/toggle-status', [UtilisateurController::class, 'toggleStatus'])
            ->name('utilisateurs.toggle-status');
        Route::resource('regions', RegionController::class);
        Route::resource('langues', LangueController::class);
        Route::resource('contenus', ContenuController::class)->except(['show']);
        Route::resource('commentaires', CommentaireController::class)->except(['store','destroy']);
        Route::resource('media', MediaController::class)->except(['index']);
        Route::resource('roles', RoleController::class);
        Route::resource('type-medias', TypeMediaController::class);
        Route::resource('type-contenus', TypeContenuController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Recherche
|--------------------------------------------------------------------------
*/

Route::get('/search', [ContenuController::class, 'search'])->name('search');

/*
|--------------------------------------------------------------------------
| Route publique pour afficher un contenu (doit être après les routes protégées)
|--------------------------------------------------------------------------
*/
Route::get('/contenus/{id}', [ContenuController::class, 'showPublic'])->name('contenus.show.public');



/*
|--------------------------------------------------------------------------
| Routes de Paiement
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Afficher la page d'achat d'un contenu
    Route::get('/contenus/{contenu}/acheter', [PaiementController::class, 'showAchat'])
        ->name('contenus.acheter.show');
    
    // Traiter le paiement (redirection vers Stripe)
    Route::post('/contenus/{contenu}/paiement', [PaiementController::class, 'processPaiement'])
        ->name('contenus.paiement.process');
    
    // Page de succès après paiement
    Route::get('/paiement/success/{contenu}', [PaiementController::class, 'success'])
        ->name('paiement.success');
    
    // Page d'annulation
    Route::get('/paiement/cancel/{contenu}', [PaiementController::class, 'cancel'])
        ->name('paiement.cancel');
    
    // Historique des paiements
    Route::get('/mes-paiements', [PaiementController::class, 'mesPaiements'])
        ->name('paiement.historique');
    
    // Route de test (À SUPPRIMER EN PRODUCTION)
    Route::get('/paiement/test/{contenu}', [PaiementController::class, 'testPaiement'])
        ->name('paiement.test');
});

// Webhook Stripe (doit être public, sans middleware auth)
Route::post('/stripe/webhook', [PaiementController::class, 'webhook'])
    ->name('stripe.webhook');

// Exclure le webhook de la vérification CSRF
// Ajoutez dans app/Http/Middleware/VerifyCsrfToken.php :
// protected $except = ['stripe/webhook'];