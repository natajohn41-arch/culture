<?php

namespace Database\Seeders\Exports;

use Illuminate\Database\Seeder;

/**
 * Seeder pour importer toutes les données exportées
 * 
 * Ce seeder charge toutes les données exportées de votre base locale
 * Pour l'utiliser, ajoutez-le dans DatabaseSeeder.php :
 * 
 * $this->call([
 *     \Database\Seeders\Exports\ExportDatabaseSeeder::class,
 * ]);
 */
class ExportDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Charger tous les seeders d'export dans l'ordre
        $seeders = [
            ExportRolesSeeder::class,
            ExportLanguesSeeder::class,
            ExportTypeMediasSeeder::class,
            ExportTypeContenusSeeder::class,
            ExportRegionsSeeder::class,
            ExportUsersSeeder::class,
            ExportContenusSeeder::class,
            ExportMediaSeeder::class,
            ExportCommentairesSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }
}








