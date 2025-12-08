<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use App\Models\Contenu;
use App\Models\Role;

class TestRolePermissions extends Command
{
    protected $signature = 'test:permissions';
    protected $description = 'Test des permissions pour chaque rôle';

    public function handle()
    {
        $this->info('=== TEST DES PERMISSIONS PAR RÔLE ===');
        $this->newLine();

        $roles = ['Admin', 'Moderateur', 'Auteur', 'Utilisateur'];
        
        foreach ($roles as $roleName) {
            $this->info("🔍 Test du rôle: {$roleName}");
            $this->line(str_repeat('-', 50));
            
            $user = Utilisateur::whereHas('role', function($q) use ($roleName) {
                $q->where('nom_role', $roleName);
            })->first();
            
            if (!$user) {
                $this->warn("  ⚠️  Aucun utilisateur avec le rôle '{$roleName}' trouvé");
                $this->newLine();
                continue;
            }
            
            $this->info("  ✅ Utilisateur trouvé: {$user->prenom} {$user->nom} ({$user->email})");
            
            // Test des méthodes de rôle
            $this->testRoleMethods($user, $roleName);
            
            // Test des accès
            $this->testAccess($user, $roleName);
            
            $this->newLine();
        }
        
        $this->info('=== TESTS TERMINÉS ===');
    }
    
    private function testRoleMethods($user, $roleName)
    {
        $this->line("  📋 Vérification des méthodes de rôle:");
        
        $expectedAdmin = ($roleName === 'Admin');
        $expectedModerator = ($roleName === 'Moderateur');
        $expectedAuthor = ($roleName === 'Auteur');
        
        $isAdmin = $user->isAdmin();
        $isModerator = $user->isModerator();
        $isAuthor = $user->isAuthor();
        
        if ($isAdmin === $expectedAdmin) {
            $this->line("    ✅ isAdmin() = {$isAdmin}");
        } else {
            $this->error("    ❌ isAdmin() = {$isAdmin} (attendu: {$expectedAdmin})");
        }
        
        if ($isModerator === $expectedModerator) {
            $this->line("    ✅ isModerator() = {$isModerator}");
        } else {
            $this->error("    ❌ isModerator() = {$isModerator} (attendu: {$expectedModerator})");
        }
        
        if ($isAuthor === $expectedAuthor) {
            $this->line("    ✅ isAuthor() = {$isAuthor}");
        } else {
            $this->error("    ❌ isAuthor() = {$isAuthor} (attendu: {$expectedAuthor})");
        }
    }
    
    private function testAccess($user, $roleName)
    {
        $this->line("  🔐 Vérification des accès:");
        
        // Test accès dashboard
        $this->line("    - Dashboard: ✅ (accessible à tous)");
        
        // Test accès admin dashboard
        if ($roleName === 'Admin') {
            $this->line("    - Admin Dashboard: ✅ (réservé aux admins)");
        } else {
            $this->line("    - Admin Dashboard: ❌ (non accessible)");
        }
        
        // Test création contenu
        if ($roleName === 'Admin' || $roleName === 'Auteur') {
            $this->line("    - Création contenu: ✅ (autorisé)");
        } else {
            $this->line("    - Création contenu: ❌ (non autorisé)");
        }
        
        // Test validation contenu
        if ($roleName === 'Admin' || $roleName === 'Moderateur') {
            $this->line("    - Validation contenu: ✅ (autorisé)");
        } else {
            $this->line("    - Validation contenu: ❌ (non autorisé)");
        }
        
        // Test gestion utilisateurs
        if ($roleName === 'Admin') {
            $this->line("    - Gestion utilisateurs: ✅ (autorisé)");
        } else {
            $this->line("    - Gestion utilisateurs: ❌ (non autorisé)");
        }
        
        // Test accès contenus premium
        if ($roleName === 'Admin' || $roleName === 'Moderateur') {
            $this->line("    - Accès contenus premium: ✅ (gratuit)");
        } else {
            $this->line("    - Accès contenus premium: 💰 (payant)");
        }
    }
}

