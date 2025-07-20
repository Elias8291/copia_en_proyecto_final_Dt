<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades_proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('actividad_id')->constrained('actividades_economicas');
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
            
            $table->unique(['proveedor_id', 'actividad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades_proveedores');
    }
};