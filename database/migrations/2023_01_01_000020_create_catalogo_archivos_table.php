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
        Schema::create('catalogo_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_documento');
            $table->text('descripcion')->nullable();
            $table->enum('aplica_a', ['Física', 'Moral', 'Ambas'])->default('Ambas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_archivos');
    }
};