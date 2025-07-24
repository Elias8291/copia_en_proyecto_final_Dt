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
        Schema::create('actividades_economicas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique(); // Código de la actividad (ej: 111110)
            $table->string('nombre', 500); // Nombre de la actividad
            $table->text('descripcion')->nullable(); // Descripción detallada
            $table->string('sector', 100)->nullable(); // Sector económico
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['nombre']);
            $table->index(['codigo']);
            $table->index(['activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_economicas');
    }
};
