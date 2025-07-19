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
            $table->foreignId('tramite_id')->constrained('tramites');
            $table->dateTime('fecha_hora_cita');
            $table->enum('tipo', ['Cotejo de Documentos', 'Aclaraciones']);
            $table->enum('estado', ['Programada', 'Completada', 'Cancelada', 'No Asistió'])->default('Programada');
            $table->foreignId('revisor_asignado_id')->nullable()->constrained('users');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['tramite_id']);
            $table->dropForeign(['revisor_asignado_id']);
        });
        
        Schema::dropIfExists('citas');
    }
};