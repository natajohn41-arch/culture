<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nom_role' => 'Admin'],
            ['nom_role' => 'Moderateur'],
            ['nom_role' => 'Auteur'],
            ['nom_role' => 'Utilisateur'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom_role' => $role['nom_role']], $role);
        }
    }
}