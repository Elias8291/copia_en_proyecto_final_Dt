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
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->enum('tipo_persona', ['Física', 'Moral', 'Ambas'])->default('Ambas');
            $table->enum('tipo_archivo', ['png', 'pdf', 'mp3', 'mp4']);
            $table->boolean('es_visible')->default(true);
            $table->timestamps();
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