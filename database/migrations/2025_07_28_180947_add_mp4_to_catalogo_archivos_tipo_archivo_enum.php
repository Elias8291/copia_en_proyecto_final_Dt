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
        // Agregar 'mp4' al enum tipo_archivo usando SQL raw
        DB::statement("ALTER TABLE catalogo_archivos MODIFY COLUMN tipo_archivo ENUM('png', 'pdf', 'mp3', 'mp4')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover 'mp4' del enum tipo_archivo
        DB::statement("ALTER TABLE catalogo_archivos MODIFY COLUMN tipo_archivo ENUM('png', 'pdf', 'mp3')");
    }
};
