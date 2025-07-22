<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('calle');
            $table->string('entre_calles')->nullable();
            $table->string('numero_exterior', 20)->nullable();
            $table->string('numero_interior', 20)->nullable();
            $table->string('codigo_postal', 10);
            $table->string('colonia_asentamiento');
            $table->string('municipio');
            $table->foreignId('id_estado')->constrained('estados');
            $table->foreignId('coordenadas_id')->nullable()->constrained('coordenadas');
            $table->foreignId('id_tramite')->nullable()->constrained('tramites');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
