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
        Schema::create('tramite_secciones_revision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites');
            $table->foreignId('seccion_id')->constrained('catalogo_secciones');
            $table->enum('estado_revision', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('comentarios_revision')->nullable();
            $table->foreignId('revisor_id')->nullable()->constrained('users');
            $table->timestamp('fecha_revision')->nullable();
            
            // Relación polimórfica
            $table->unsignedBigInteger('contenido_id');
            $table->string('contenido_type');
            
            $table->unique(['tramite_id', 'seccion_id']);
            $table->index(['contenido_id', 'contenido_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramite_secciones_revision');
    }
};