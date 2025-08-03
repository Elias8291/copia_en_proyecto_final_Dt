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
        Schema::create('revisiones_tramite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->enum('tipo_revision', ['Digital', 'Presencial', 'Domiciliaria']);
            $table->foreignId('revisor_id')->constrained('users')->onDelete('cascade');
            $table->enum('estado', ['Pendiente', 'En_Proceso', 'Finalizada'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable();
            $table->tinyInteger('intento')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisiones_tramite');
    }
}; 