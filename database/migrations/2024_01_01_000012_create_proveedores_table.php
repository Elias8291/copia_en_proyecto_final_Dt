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
            $table->foreignId('usuario_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('pv_numero', 20);
            $table->string('rfc', 13);
            $table->enum('tipo_persona', ['Física', 'Moral']);
            $table->enum('estado_padron', ['Activo', 'Inactivo', 'Vencido', 'Pendiente', 'Cancelado']);
            $table->date('fecha_alta_padron');
            $table->date('fecha_vencimiento_padron');
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