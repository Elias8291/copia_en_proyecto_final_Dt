<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asentamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 405);
            $table->string('codigo_postal', 5);
            $table->foreignId('localidad_id')->constrained('localidades');
            $table->foreignId('tipo_asentamiento_id')->constrained('tipos_asentamiento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asentamientos');
    }
};
