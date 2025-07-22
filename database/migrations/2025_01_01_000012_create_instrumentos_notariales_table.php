<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrumentos_notariales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_escritura');
            $table->string('numero_escritura_constitutiva');
            $table->date('fecha_constitucion');
            $table->string('nombre_notario');
            $table->string('entidad_federativa');
            $table->integer('numero_notario');
            $table->string('numero_registro_publico');
            $table->date('fecha_inscripcion');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumentos_notariales');
    }
};
