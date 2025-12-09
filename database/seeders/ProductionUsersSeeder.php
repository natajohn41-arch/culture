<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Langue;
use Illuminate\Support\Facades\Hash;

class ProductionUsersSeeder extends Seeder
{
    /**
     * Seed des utilisateurs de production
     * Configurez les utilisateurs via les variables d'environnement ou modifiez directement ce fichier
     */
    public function run(): void
    {
        $defaultLang = Langue::first();
        if (!$defaultLang) {
            $this->command->warn('Aucune langue trouvée. Créez d\'abord les langues.');
            return;
        }

        // Récupérer les rôles
        $roles = Role::all()->keyBy('nom_role');

        // Liste des utilisateurs de production à créer
        // Vous pouvez modifier cette liste ou utiliser des variables d'environnement
        $productionUsers = [
            [
                'nom' => env('ADMIN_NOM', 'Admin'),
                'prenom' => env('ADMIN_PRENOM', 'Principal'),
                'email' => env('ADMIN_EMAIL', 'admin@culture.bj'),
                'password' => env('ADMIN_PASSWORD', 'ChangeMe123!'),
                'role' => 'Admin',
                'statut' => 'actif',
            ],
            // Ajoutez d'autres utilisateurs ici si nécessaire
            // [
            //     'nom' => 'Votre',
            //     'prenom' => 'Nom',
            //     'email' => 'votre.email@example.com',
            //     'password' => 'VotreMotDePasse',
            //     'role' => 'Auteur',
            //     'statut' => 'actif',
            // ],
        ];

        foreach ($productionUsers as $userData) {
            // Vérifier si l'utilisateur existe déjà
            if (Utilisateur::where('email', $userData['email'])->exists()) {
                $this->command->info("Utilisateur {$userData['email']} existe déjà, ignoré.");
                continue;
            }

            // Vérifier que le rôle existe
            $role = $roles->get($userData['role']);
            if (!$role) {
                $this->command->warn("Rôle '{$userData['role']}' non trouvé pour {$userData['email']}, ignoré.");
                continue;
            }

            // Créer l'utilisateur
            Utilisateur::create([
                'nom' => $userData['nom'],
                'prenom' => $userData['prenom'],
                'email' => $userData['email'],
                'mot_de_passe' => Hash::make($userData['password']),
                'sexe' => null,
                'date_naissance' => null,
                'photo' => null,
                'id_role' => $role->id_role,
                'id_langue' => $defaultLang->id_langue,
                'date_inscription' => now(),
                'statut' => $userData['statut'] ?? 'actif',
                'remember_token' => null,
            ]);

            $this->command->info("Utilisateur de production créé : {$userData['email']} (Rôle: {$userData['role']})");
        }
    }
}

