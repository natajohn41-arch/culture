<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la colonne 'Prenom' existe et la renommer en 'prenom'
        if (Schema::hasColumn('utilisateurs', 'Prenom')) {
            DB::statement('ALTER TABLE utilisateurs RENAME COLUMN "Prenom" TO prenom');
        }
        
        // Vérifier si la colonne 'mot de passe' existe et la renommer en 'mot_de_passe'
        if (Schema::hasColumn('utilisateurs', 'mot de passe')) {
            DB::statement('ALTER TABLE utilisateurs RENAME COLUMN "mot de passe" TO mot_de_passe');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renommer 'prenom' en 'Prenom' pour rollback
        if (Schema::hasColumn('utilisateurs', 'prenom')) {
            DB::statement('ALTER TABLE utilisateurs RENAME COLUMN prenom TO "Prenom"');
        }
        
        // Renommer 'mot_de_passe' en 'mot de passe' pour rollback
        if (Schema::hasColumn('utilisateurs', 'mot_de_passe')) {
            DB::statement('ALTER TABLE utilisateurs RENAME COLUMN mot_de_passe TO "mot de passe"');
        }
    }
};
