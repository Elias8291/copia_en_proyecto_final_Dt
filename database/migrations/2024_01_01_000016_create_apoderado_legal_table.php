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
        Schema::create('apoderado_legal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrumento_notarial_id')->nullable()->constrained('instrumentos_notariales')->onDelete('set null');
            $table->string('nombre_apoderado', 255);
            $table->string('rfc', 255);
            $table->string('numero_escritura_constitutiva_poder', 255)->nullable();
            $table->string('numero_registro_publico_poder', 255)->nullable();
            $table->date('fecha_inscripcion_poder')->nullable();
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
        Schema::dropIfExists('apoderado_legal');
    }
}; 