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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('id_utilisateur');
            $table->string('nom');
            $table->string('Prenom');
            $table->string('email');
            $table->string('mot de passe');
            $table->foreignId('id_role');
            $table->foreignId('id_langue');
            $table->string('sexe');
            $table->string('photo');
            $table->string('statut');   
            $table->date('date_naissance');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
