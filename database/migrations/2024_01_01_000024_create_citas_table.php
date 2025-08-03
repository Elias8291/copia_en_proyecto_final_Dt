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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->enum('tipo_cita', ['Presencial', 'Domiciliaria', 'Digital']);
            $table->timestamp('fecha_cita');
            $table->enum('estado', ['Asignada', 'Cancelada', 'Asistida', 'No_Asistio'])->default('Asignada');
            $table->tinyInteger('intento')->default(1);
            $table->foreignId('asignado_a')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
}; 