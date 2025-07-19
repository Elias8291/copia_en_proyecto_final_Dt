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
        Schema::create('catalogo_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('catalogo_sectores');
            $table->string('codigo_scian', 20)->nullable();
            $table->string('nombre', 500);
            $table->enum('estado', ['Aprobada', 'Pendiente de Revisión'])->default('Aprobada');
            $table->foreignId('creada_por_usuario_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_actividades');
    }
};