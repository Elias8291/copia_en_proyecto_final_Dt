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
        Schema::create('accionistas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->decimal('porcentaje_participacion', 5, 2);
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->string('rfc', 255);
            $table->enum('status', ['pendiente', 'vigente', 'historico', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accionistas');
    }
}; 