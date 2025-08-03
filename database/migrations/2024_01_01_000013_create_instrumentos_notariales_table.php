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
        Schema::create('instrumentos_notariales', function (Blueprint $table) {
            $table->id();
            $table->string('numero_escritura', 255);
            $table->string('numero_escritura_constitutiva', 255);
            $table->date('fecha_constitucion');
            $table->string('nombre_notario', 255);
            $table->foreignId('estado_id')->constrained('estados')->onDelete('cascade');
            $table->integer('numero_notario');
            $table->string('numero_registro_publico', 255);
            $table->date('fecha_inscripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumentos_notariales');
    }
}; 