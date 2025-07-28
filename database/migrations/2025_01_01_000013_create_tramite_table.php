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
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->enum('tipo_tramite', ['Inscripcion', 'Renovacion', 'Actualizacion']);
            $table->enum('estado', ['Pendiente', 'En_Revision', 'Aprobado', 'Rechazado', 'Por_Cotejar', 'Para_Correccion', 'Cancelado'])->default('Pendiente');
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedTinyInteger('paso_actual')->default(1);

            $table->timestamps();
        });

        // Crear tabla de notificaciones
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->enum('tipo', ['Sistema', 'Tramite', 'Cita', 'Vencimiento', 'info', 'warning', 'error', 'success'])->default('Sistema');
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('tramites');
    }
};
