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
        Schema::create('datos_generales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->string('curp', 18)->nullable();
            $table->string('razon_social', 255);
            $table->string('pagina_web', 255)->nullable();
            $table->string('telefono', 50);
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->enum('status', ['pendiente', 'vigente', 'historico', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_generales');
    }
}; 