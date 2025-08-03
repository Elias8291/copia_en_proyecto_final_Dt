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
        Schema::create('asentamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo_postal', 10)->nullable();
            $table->unsignedBigInteger('localidad_id')->nullable();
            $table->foreignId('tipo_asentamiento_id')->constrained('tipos_asentamiento')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asentamientos');
    }
}; 