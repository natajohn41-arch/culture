<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Langue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersPerRoleSeeder extends Seeder
{
    public function run(): void
    {
        $defaultLang = Langue::first();
        if (! $defaultLang) {
            $defaultLang = Langue::create([
                'nom_langue' => 'Français',
                'code_langue' => 'fr',
                'description' => 'Langue par défaut',
            ]);
        }

        $roles = Role::all();
        foreach ($roles as $role) {
            $email = Str::slug($role->nom_role ?: 'role') . '@example.test';

            if (Utilisateur::where('email', $email)->exists()) {
                continue;
            }
            Utilisateur::create([
                'nom' => ucfirst($role->nom_role ?? 'Nom'),
                'prenom' => 'Compte',
                'email' => $email,
                'mot_de_passe' => Hash::make('password'),
                'sexe' => null,
                'date_naissance' => null,
                'photo' => null,
                'id_role' => $role->id_role ?? $role->id,
                'id_langue' => $defaultLang->id_langue ?? $defaultLang->id,
                'date_inscription' => now(),
                'statut' => 'actif',
                'remember_token' => null,
            ]);
        }
    }
}
