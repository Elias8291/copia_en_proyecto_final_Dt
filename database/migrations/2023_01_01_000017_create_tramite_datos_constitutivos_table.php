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
        Schema::create('tramite_datos_constitutivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->unique()->constrained('tramites');
            $table->string('numero_instrumento_notarial', 100)->nullable();
            $table->date('fecha_instrumento_notarial')->nullable();
            $table->string('nombre_notario_publico')->nullable();
            $table->string('numero_notaria', 50)->nullable();
            $table->string('entidad_federativa_notaria', 100)->nullable();
            $table->string('folio_mercantil', 50)->nullable();
            $table->date('fecha_constitucion')->nullable();
            $table->decimal('capital_social', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramite_datos_constitutivos');
    }
};