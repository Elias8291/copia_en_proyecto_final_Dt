<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades_economicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sectores');
            $table->string('nombre');
            $table->string('codigo_scian', 10)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('fuente', 50)->default('MANUAL');
            $table->enum('estado_validacion', ['Pendiente', 'Validada', 'Rechazada'])->default('Pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades_economicas');
    }
};
