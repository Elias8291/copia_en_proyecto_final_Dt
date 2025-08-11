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
        Schema::table('instrumentos_notariales', function (Blueprint $table) {
            // Cambiar el tipo de dato de integer a bigInteger para permitir números más grandes
            $table->bigInteger('numero_notario')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instrumentos_notariales', function (Blueprint $table) {
            // Revertir el cambio de bigInteger a integer
            $table->integer('numero_notario')->change();
        });
    }
};
