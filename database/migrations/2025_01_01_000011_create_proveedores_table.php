<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('pv_numero', 20)->unique()->nullable();
            $table->string('rfc', 13);
            $table->enum('tipo_persona', ['Física', 'Moral']);
            $table->enum('estado_padron', ['Activo', 'Inactivo', 'Vencido', 'Pendiente', 'Cancelado'])->default('Pendiente');
            $table->date('fecha_alta_padron')->nullable();
            $table->date('fecha_vencimiento_padron')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
