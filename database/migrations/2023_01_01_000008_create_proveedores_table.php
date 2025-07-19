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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->string('rfc', 13)->unique();
            $table->string('curp', 18)->nullable();
            $table->enum('tipo_persona', ['Física', 'Moral']);
            $table->string('razon_social')->nullable();
            $table->string('pv_numero', 20)->unique()->nullable();
            $table->enum('estado_padron', ['Activo', 'Inactivo', 'Vencido', 'Pendiente'])->default('Pendiente');
            $table->date('fecha_alta_padron')->nullable();
            $table->date('fecha_vencimiento_padron')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};