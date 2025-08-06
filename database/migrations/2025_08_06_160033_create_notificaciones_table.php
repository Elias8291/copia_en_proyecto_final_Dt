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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['informativo', 'advertencia', 'error', 'exito', 'Tramite', 'Cita'])
                  ->default('informativo');
            $table->string('titulo', 255);
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->json('datos_adicionales')->nullable(); // Para almacenar datos extra como IDs de trámites, etc.
            $table->string('accion_url')->nullable(); // URL opcional para redireccionar al hacer click
            $table->timestamp('fecha_lectura')->nullable();
            $table->timestamps();
            
            // Índices para mejorar el rendimiento
            $table->index(['usuario_id', 'leida']);
            $table->index(['usuario_id', 'created_at']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
