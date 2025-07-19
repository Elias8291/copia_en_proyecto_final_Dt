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
        Schema::create('direcciones_no_catalogadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('direccion_id')->unique()->constrained('direcciones');
            $table->string('colonia_texto');
            $table->string('municipio_texto');
            $table->string('estado_texto');
            $table->string('pais_texto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones_no_catalogadas', function (Blueprint $table) {
            $table->dropForeign(['direccion_id']);
        });
        
        Schema::dropIfExists('direcciones_no_catalogadas');
    }
};