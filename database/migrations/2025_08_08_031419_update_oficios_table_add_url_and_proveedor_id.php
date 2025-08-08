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
        Schema::table('oficios', function (Blueprint $table) {
            $table->string('url', 500)->nullable()->after('contenido');
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade')->after('tramite_id');
            $table->string('estado', 50)->default('Generado')->after('proveedor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oficios', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn(['url', 'proveedor_id', 'estado']);
        });
    }
};
