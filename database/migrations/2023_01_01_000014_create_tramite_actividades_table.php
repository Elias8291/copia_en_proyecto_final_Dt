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
        Schema::create('tramite_actividades', function (Blueprint $table) {
            $table->foreignId('tramite_id')->constrained('tramites');
            $table->foreignId('actividad_id')->constrained('catalogo_actividades');
            $table->boolean('es_principal')->default(false);
            
            $table->primary(['tramite_id', 'actividad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tramite_actividades', function (Blueprint $table) {
            $table->dropForeign(['tramite_id']);
            $table->dropForeign(['actividad_id']);
        });
        
        Schema::dropIfExists('tramite_actividades');
    }
};