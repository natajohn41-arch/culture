<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            // Ajouter la colonne date_inscription si elle n'existe pas
            if (!Schema::hasColumn('utilisateurs', 'date_inscription')) {
                $table->dateTime('date_inscription')->nullable()->after('id_langue');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            if (Schema::hasColumn('utilisateurs', 'date_inscription')) {
                $table->dropColumn('date_inscription');
            }
        });
    }
};
