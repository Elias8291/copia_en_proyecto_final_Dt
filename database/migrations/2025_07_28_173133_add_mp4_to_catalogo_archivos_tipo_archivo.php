<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalogo_archivos', function (Blueprint $table) {
            // Modificar el enum para incluir 'mp4'
            DB::statement("ALTER TABLE catalogo_archivos MODIFY COLUMN tipo_archivo ENUM('png', 'pdf', 'mp3', 'mp4')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogo_archivos', function (Blueprint $table) {
            // Revertir el enum para excluir 'mp4'
            DB::statement("ALTER TABLE catalogo_archivos MODIFY COLUMN tipo_archivo ENUM('png', 'pdf', 'mp3')");
        });
    }
};
