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
        Schema::table('contenus', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas avant de les ajouter
            if (!Schema::hasColumn('contenus', 'est_premium')) {
                $table->boolean('est_premium')->default(false)->after('statut');
            }
            if (!Schema::hasColumn('contenus', 'prix')) {
                $table->decimal('prix', 10, 2)->nullable()->after('est_premium');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contenus', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les supprimer
            if (Schema::hasColumn('contenus', 'prix')) {
                $table->dropColumn('prix');
            }
            if (Schema::hasColumn('contenus', 'est_premium')) {
                $table->dropColumn('est_premium');
            }
        });
    }
};

