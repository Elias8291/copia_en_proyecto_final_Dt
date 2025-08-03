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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_contacto', 255);
            $table->string('cargo', 255);
            $table->string('correo_electronico', 255);
            $table->string('telefono', 50);
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
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
        Schema::dropIfExists('contactos');
    }
}; 