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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('calle');
            $table->string('numero_exterior', 50)->nullable();
            $table->string('numero_interior', 50)->nullable();
            $table->string('codigo_postal', 10);
            $table->text('referencias')->nullable();
            $table->foreignId('asentamiento_id')->nullable()->constrained('asentamientos');
            $table->foreignId('geolocalizacion_id')->nullable()->constrained('geolocalizaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones', function (Blueprint $table) {
            $table->dropForeign(['asentamiento_id']);
            $table->dropForeign(['geolocalizacion_id']);
        });
        
        Schema::dropIfExists('direcciones');
    }
}; 