<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->enum('tipo_tramite', ['Inscripcion', 'Renovacion', 'Actualizacion']);
            $table->enum('estado', ['Pendiente', 'En_Revision', 'Aprobado', 'Rechazado', 'Por_Cotejar', 'Para_Correccion', 'Cancelado'])->default('Pendiente');
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('procesado_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};