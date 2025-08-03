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
        Schema::create('secciones_revision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->enum('seccion', ['datos_generales', 'actividades', 'domicilio', 'constitucion', 'accionistas', 'apoderado', 'documentos']);
            $table->enum('estado', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('comentario')->nullable();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secciones_revision');
    }
}; 