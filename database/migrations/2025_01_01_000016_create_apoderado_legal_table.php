<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apoderado_legal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrumento_notarial_id')->constrained('instrumentos_notariales');
            $table->string('nombre_apoderado');
            $table->string('rfc');
            $table->foreignId('tramite_id')->nullable()->constrained('tramites')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apoderado_legal');
    }
};
