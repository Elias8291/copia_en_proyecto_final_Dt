<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accionistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->nullable()->constrained('tramites')->onDelete('cascade');
            $table->string('nombre_completo');
            $table->string('rfc', 13)->nullable();
            $table->decimal('porcentaje_participacion', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('accionistas', function (Blueprint $table) {
            $table->dropForeign(['tramite_id']);
        });
        Schema::dropIfExists('accionistas');
    }
};
