<?php

namespace Tests;

use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Contenu;
use App\Models\Commentaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test des permissions pour le rôle ADMIN
     */
    public function test_admin_permissions()
    {
        $admin = Utilisateur::whereHas('role', function($q) {
            $q->where('nom_role', 'Admin');
        })->first();

        if (!$admin) {
            $this->markTestSkipped('Aucun admin trouvé');
            return;
        }

        $this->actingAs($admin);

        // Test accès dashboard admin
        $response = $this->get('/admin-dashboard');
        $response->assertStatus(200);

        // Test accès gestion utilisateurs
        $response = $this->get('/utilisateurs');
        $response->assertStatus(200);

        // Test accès gestion régions
        $response = $this->get('/regions');
        $response->assertStatus(200);

        // Test accès gestion contenus
        $response = $this->get('/contenus');
        $response->assertStatus(200);

        // Test création contenu (admin peut créer)
        $response = $this->get('/mes/contenus/create');
        $response->assertStatus(200);

        // Test validation contenus
        $contenu = Contenu::where('statut', 'en_attente')->first();
        if ($contenu) {
            $response = $this->post("/contenus/{$contenu->id_contenu}/valider");
            $response->assertStatus(302); // Redirect
        }
    }

    /**
     * Test des permissions pour le rôle MODERATEUR
     */
    public function test_moderator_permissions()
    {
        $moderator = Utilisateur::whereHas('role', function($q) {
            $q->where('nom_role', 'Moderateur');
        })->first();

        if (!$moderator) {
            $this->markTestSkipped('Aucun modérateur trouvé');
            return;
        }

        $this->actingAs($moderator);

        // Test accès dashboard utilisateur
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // Test accès contenus à valider
        $response = $this->get('/contenus-a-valider');
        $response->assertStatus(200);

        // Test validation contenus
        $contenu = Contenu::where('statut', 'en_attente')->first();
        if ($contenu) {
            $response = $this->post("/contenus/{$contenu->id_contenu}/valider");
            $response->assertStatus(302);
        }

        // Test NE PEUT PAS créer de contenu
        $response = $this->get('/mes/contenus/create');
        $response->assertStatus(403);

        // Test NE PEUT PAS gérer utilisateurs
        $response = $this->get('/utilisateurs');
        $response->assertStatus(403);

        // Test NE PEUT PAS gérer régions
        $response = $this->get('/regions');
        $response->assertStatus(403);
    }

    /**
     * Test des permissions pour le rôle AUTEUR
     */
    public function test_author_permissions()
    {
        $author = Utilisateur::whereHas('role', function($q) {
            $q->where('nom_role', 'Auteur');
        })->first();

        if (!$author) {
            $this->markTestSkipped('Aucun auteur trouvé');
            return;
        }

        $this->actingAs($author);

        // Test accès dashboard utilisateur
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // Test peut créer contenu
        $response = $this->get('/mes/contenus/create');
        $response->assertStatus(200);

        // Test peut voir ses contenus
        $response = $this->get('/mes/contenus');
        $response->assertStatus(200);

        // Test NE PEUT PAS valider contenus
        $contenu = Contenu::where('statut', 'en_attente')->first();
        if ($contenu) {
            $response = $this->post("/contenus/{$contenu->id_contenu}/valider");
            $response->assertStatus(403);
        }

        // Test NE PEUT PAS gérer utilisateurs
        $response = $this->get('/utilisateurs');
        $response->assertStatus(403);

        // Test peut modifier son propre contenu
        $monContenu = Contenu::where('id_auteur', $author->id_utilisateur)->first();
        if ($monContenu) {
            $response = $this->get("/mes/contenus/{$monContenu->id_contenu}/edit");
            $response->assertStatus(200);
        }
    }

    /**
     * Test des permissions pour le rôle UTILISATEUR
     */
    public function test_user_permissions()
    {
        $user = Utilisateur::whereHas('role', function($q) {
            $q->where('nom_role', 'Utilisateur');
        })->first();

        if (!$user) {
            $this->markTestSkipped('Aucun utilisateur trouvé');
            return;
        }

        $this->actingAs($user);

        // Test accès dashboard utilisateur
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // Test NE PEUT PAS créer contenu
        $response = $this->get('/mes/contenus/create');
        $response->assertStatus(403);

        // Test NE PEUT PAS valider contenus
        $contenu = Contenu::where('statut', 'en_attente')->first();
        if ($contenu) {
            $response = $this->post("/contenus/{$contenu->id_contenu}/valider");
            $response->assertStatus(403);
        }

        // Test NE PEUT PAS gérer utilisateurs
        $response = $this->get('/utilisateurs');
        $response->assertStatus(403);

        // Test peut voir contenus publics
        $contenuValide = Contenu::where('statut', 'valide')->first();
        if ($contenuValide) {
            $response = $this->get("/contenus/{$contenuValide->id_contenu}");
            $response->assertStatus(200);
        }

        // Test peut commenter
        if ($contenuValide) {
            $response = $this->post('/commentaires', [
                'id_contenu' => $contenuValide->id_contenu,
                'texte' => 'Test commentaire'
            ]);
            $response->assertStatus(302); // Redirect after creation
        }
    }

    /**
     * Test accès aux contenus premium selon les rôles
     */
    public function test_premium_content_access()
    {
        $premiumContenu = Contenu::where('est_premium', true)->first();
        
        if (!$premiumContenu) {
            $this->markTestSkipped('Aucun contenu premium trouvé');
            return;
        }

        // Test admin a accès gratuit
        $admin = Utilisateur::whereHas('role', function($q) {
            $q->where('nom_role', 'Admin');
        })->first();
        
        if ($admin) {
            $this->actingAs($admin);
            $response = $this->get("/contenus/{$premiumContenu->id_contenu}");
            $response->assertStatus(200);
        }

        // Test modérateur a accès gratuit
        $moderator = Utilisateur::whereHas('role', function($q) {
            $q->where('nom_role', 'Moderateur');
        })->first();
        
        if ($moderator) {
            $this->actingAs($moderator);
            $response = $this->get("/contenus/{$premiumContenu->id_contenu}");
            $response->assertStatus(200);
        }
    }
}
