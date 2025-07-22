<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_constitutivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrumento_notarial_id')->constrained('instrumentos_notariales');
            $table->foreignId('tramite_id')->nullable()->constrained('tramites');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datos_constitutivos');
    }
};
