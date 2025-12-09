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
        Schema::dropIfExists('media');
        Schema::create('media', function (Blueprint $table) {
            $table->id('id_media');
            $table->string('id_type_media');
            $table->text('description');
            $table->string('chemin');
            $table->foreignId('id_contenu');
            $table->string('fichier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
