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
            $table->foreignId('tramite_id')->constrained('tramites');
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->timestamp('fecha_cita');
            $table->enum('tipo_cita', ['Revision', 'Cotejo', 'Entrega']);
            $table->enum('estado', ['Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada'])->default('Programada');
            $table->foreignId('atendido_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
