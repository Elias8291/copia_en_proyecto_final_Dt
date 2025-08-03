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
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->enum('tipo_tramite', ['Inscripcion', 'Renovacion', 'Actualizacion']);
            $table->enum('status', ['Pendiente', 'En_Revision', 'Aprobado', 'Rechazado', 'Por_Cotejar', 'Para_Correccion', 'Cancelado'])->default('Pendiente');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->timestamp('fecha_cancelacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->integer('correcciones_count')->default(0);
            $table->tinyInteger('paso_actual')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
}; 