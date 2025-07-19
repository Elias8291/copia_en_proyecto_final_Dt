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
        Schema::create('tramite_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites');
            $table->foreignId('catalogo_archivo_id')->constrained('catalogo_archivos');
            $table->string('nombre_original_archivo');
            $table->string('path_almacenamiento', 500);
            $table->string('hash_archivo', 64)->nullable();
            $table->enum('estado_revision', ['Pendiente', 'Aprobado', 'Rechazado con Comentarios'])->default('Pendiente');
            $table->text('comentarios_revision')->nullable();
            $table->foreignId('revisor_id')->nullable()->constrained('users');
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramite_archivos');
    }
};