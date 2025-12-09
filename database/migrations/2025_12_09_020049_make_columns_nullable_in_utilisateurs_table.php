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
        // Rendre les colonnes nullable en PostgreSQL
        if (Schema::hasColumn('utilisateurs', 'photo')) {
            DB::statement('ALTER TABLE utilisateurs ALTER COLUMN photo DROP NOT NULL');
        }
        if (Schema::hasColumn('utilisateurs', 'sexe')) {
            DB::statement('ALTER TABLE utilisateurs ALTER COLUMN sexe DROP NOT NULL');
        }
        if (Schema::hasColumn('utilisateurs', 'date_naissance')) {
            DB::statement('ALTER TABLE utilisateurs ALTER COLUMN date_naissance DROP NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les colonnes en NOT NULL (avec une valeur par défaut pour les valeurs existantes)
        if (Schema::hasColumn('utilisateurs', 'photo')) {
            DB::statement("UPDATE utilisateurs SET photo = '' WHERE photo IS NULL");
            DB::statement('ALTER TABLE utilisateurs ALTER COLUMN photo SET NOT NULL');
        }
        if (Schema::hasColumn('utilisateurs', 'sexe')) {
            DB::statement("UPDATE utilisateurs SET sexe = 'M' WHERE sexe IS NULL");
            DB::statement('ALTER TABLE utilisateurs ALTER COLUMN sexe SET NOT NULL');
        }
        if (Schema::hasColumn('utilisateurs', 'date_naissance')) {
            DB::statement("UPDATE utilisateurs SET date_naissance = '2000-01-01' WHERE date_naissance IS NULL");
            DB::statement('ALTER TABLE utilisateurs ALTER COLUMN date_naissance SET NOT NULL');
        }
    }
};
