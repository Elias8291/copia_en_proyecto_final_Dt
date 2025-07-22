<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->nullable()->constrained('tramites');
            $table->foreignId('actividad_id')->constrained('actividades_economicas');
            $table->timestamps();

            $table->unique(['tramite_id', 'actividad_id']);
        });
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropForeign(['tramite_id']);
            $table->dropForeign(['actividad_id']);
        });
        Schema::dropIfExists('actividades');
    }
};
