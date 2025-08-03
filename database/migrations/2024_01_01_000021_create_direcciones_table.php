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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            
            $table->string('calle', 255);
            $table->string('numero_exterior', 20); // Puede contener números y letras
            $table->string('numero_interior', 20)->nullable(); // Puede contener números y letras
            $table->string('colonia', 255);
            $table->string('codigo_postal', 10);
            
            $table->string('municipio', 100);
            $table->string('asentamiento', 100);
            $table->foreignId('coordenada_id')->nullable()->constrained('coordenadas')->onDelete('set null');
            $table->foreignId('estado_id')->constrained('estados')->onDelete('cascade');
            
            $table->enum('status', ['pendiente', 'vigente', 'historico', 'rechazado'])->default('pendiente');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
}; 