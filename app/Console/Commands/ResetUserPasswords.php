<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-passwords {--password=password : Le mot de passe à définir pour tous les utilisateurs} {--email= : Réinitialiser seulement pour cet email} {--force : Forcer la réinitialisation sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réinitialise les mots de passe des utilisateurs (utile pour corriger les problèmes de connexion)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->option('password');
        $email = $this->option('email');
        
        if ($email) {
            $user = Utilisateur::where('email', $email)->first();
            if (!$user) {
                $this->error("Utilisateur avec l'email '{$email}' non trouvé.");
                return 1;
            }
            
            $user->mot_de_passe = Hash::make($password);
            $user->save();
            
            $this->info("✅ Mot de passe réinitialisé pour {$email}");
            $this->info("   Email: {$user->email}");
            $this->info("   Nouveau mot de passe: {$password}");
            return 0;
        }
        
        // Réinitialiser tous les utilisateurs
        if (!$this->option('force') && !$this->confirm('Voulez-vous réinitialiser les mots de passe de TOUS les utilisateurs ?', true)) {
            $this->info('Opération annulée.');
            return 0;
        }
        
        $users = Utilisateur::all();
        $count = 0;
        
        foreach ($users as $user) {
            $user->mot_de_passe = Hash::make($password);
            $user->save();
            $count++;
        }
        
        $this->info("✅ {$count} mot(s) de passe réinitialisé(s) avec succès !");
        $this->info("   Nouveau mot de passe pour tous: {$password}");
        $this->warn("   ⚠️  N'oubliez pas de changer ces mots de passe après la connexion !");
        
        return 0;
    }
}
