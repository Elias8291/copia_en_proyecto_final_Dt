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
        Schema::table('citas', function (Blueprint $table) {
            // Agregar user_id
            $table->foreignId('user_id')->nullable()->after('tramite_id')->constrained('users')->onDelete('cascade');
            
            // Eliminar proveedor_id
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn('proveedor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Revertir: agregar proveedor_id
            $table->foreignId('proveedor_id')->nullable()->after('tramite_id')->constrained('proveedores')->onDelete('cascade');
            
            // Revertir: eliminar user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
