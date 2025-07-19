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
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('direccion_id')->nullable()->constrained('direcciones');
            $table->enum('tipo', ['Inscripción', 'Renovación', 'Actualización']);
            $table->enum('estado', [
                'Borrador', 
                'En Revisión', 
                'Requiere Correcciones', 
                'En Cotejo', 
                'Aprobado', 
                'Rechazado'
            ])->default('Borrador');
            $table->timestamp('fecha_solicitud')->nullable();
            $table->timestamp('fecha_aprobacion_rechazo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropForeign(['direccion_id']);
        });
        
        Schema::dropIfExists('tramites');
    }
};