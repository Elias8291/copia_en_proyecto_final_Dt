<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_generales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->nullable()->constrained('tramites');
            $table->string('rfc', 13)->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('razon_social')->nullable();
            $table->string('pagina_web')->nullable();
            $table->string('telefono', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('datos_generales', function (Blueprint $table) {
            $table->dropForeign(['tramite_id']);
        });
        Schema::dropIfExists('datos_generales');
    }
};
