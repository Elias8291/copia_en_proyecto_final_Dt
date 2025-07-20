<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_original');
            $table->string('ruta_archivo', 500);
            $table->foreignId('idCatalogoArchivo')->constrained('catalogo_archivos');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_cotejo')->nullable();
            $table->foreignId('cotejado_por')->nullable()->constrained('users');
            $table->boolean('aprobado')->default(false);
            $table->foreignId('tramite_id')->nullable()->constrained('tramites');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};