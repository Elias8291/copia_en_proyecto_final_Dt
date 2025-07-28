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
            $table->foreignId('tramite_id')->nullable()->constrained('tramites')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_tramite')->nullable();
            $table->dateTime('fecha_cita');
            $table->enum('tipo_cita', ['Revision', 'Cotejo', 'Entrega', 'Consulta', 'Otro', 'Reunion', 'Administrativa']);
            $table->enum('estado', ['Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada'])->default('Programada');
            $table->foreignId('atendido_por')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observaciones')->nullable();
            $table->text('motivo')->nullable();
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
