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
        Schema::create('dias_inhabiles', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('tipo', ['Vacaciones', 'Feriado', 'Otro'])->default('Feriado');
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['fecha_inicio', 'fecha_fin']);
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_inhabiles');
    }
}; 