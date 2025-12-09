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
        if (Schema::hasColumn('utilisateurs', 'remember_token')) {
            Schema::table('utilisateurs', function (Blueprint $table) {
                $table->string('remember_token')->nullable()->change();
            });
        } else {
            Schema::table('utilisateurs', function (Blueprint $table) {
                $table->string('remember_token')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            if (Schema::hasColumn('utilisateurs', 'remember_token')) {
                $table->string('remember_token')->nullable(false)->change();
            }
        });
    }
};
