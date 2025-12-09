<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Core reference data
        $this->call([
            RoleSeeder::class,
            LangueSeeder::class,
            TypeMediaSeeder::class,
            TypeContenuSeeder::class,
            RegionSeeder::class,
        ]);

        // Create one user per role (test users)
        $this->call([
            UsersPerRoleSeeder::class,
        ]);

        // Create production users (if configured)
        $this->call([
            ProductionUsersSeeder::class,
        ]);

        // Sample contents, medias and commentaires (optional)
        if (class_exists(\Database\Seeders\ContentSeeder::class)) {
            $this->call([
                ContentSeeder::class,
            ]);
        }

        // Complete content seeders
        $this->call([
            CompleteContentSeeder::class,
            CompleteRegionContentSeeder::class,
        ]);

        // Import des données exportées de la base locale (si disponibles)
        if (class_exists(\Database\Seeders\Exports\ExportDatabaseSeeder::class)) {
            $this->call([
                \Database\Seeders\Exports\ExportDatabaseSeeder::class,
            ]);
        }
    }
}
