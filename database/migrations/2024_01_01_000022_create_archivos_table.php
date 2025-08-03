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
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->string('nombre_original', 255);
            $table->string('nombre_archivo', 255);
            $table->string('ruta', 500);
            $table->string('extension', 10);
            $table->bigInteger('tamaño');
            $table->foreignId('catalogo_archivo_id')->constrained('catalogo_archivos')->onDelete('cascade');
            $table->enum('status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
}; 