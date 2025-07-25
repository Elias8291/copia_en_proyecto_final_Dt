<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->nullable()->constrained('tramites')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('cascade');
            $table->timestamp('fecha_cita');
            $table->enum('tipo_cita', ['Revision', 'Cotejo', 'Entrega', 'Consulta', 'Otro', 'Reunion', 'Administrativa']);
            $table->enum('estado', ['Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada'])->default('Programada');
            $table->string('motivo', 200)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('atendido_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
