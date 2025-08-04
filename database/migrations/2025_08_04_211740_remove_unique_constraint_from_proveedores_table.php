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
        Schema::table('proveedores', function (Blueprint $table) {
            // Eliminar la clave foránea primero
            $table->dropForeign(['usuario_id']);
            // Eliminar la restricción única
            $table->dropUnique(['usuario_id']);
            // Volver a agregar la clave foránea sin restricción única
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['usuario_id']);
            // Restaurar la restricción única
            $table->unique('usuario_id');
            // Volver a agregar la clave foránea con restricción única
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
