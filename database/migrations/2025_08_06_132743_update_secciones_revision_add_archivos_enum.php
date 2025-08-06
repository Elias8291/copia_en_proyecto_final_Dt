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
        // Actualizar el enum para incluir 'archivos' en lugar de 'documentos'
        DB::statement("ALTER TABLE secciones_revision MODIFY COLUMN seccion ENUM('datos_generales', 'actividades', 'domicilio', 'constitucion', 'accionistas', 'apoderado', 'archivos')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al enum original con 'documentos'
        DB::statement("ALTER TABLE secciones_revision MODIFY COLUMN seccion ENUM('datos_generales', 'actividades', 'domicilio', 'constitucion', 'accionistas', 'apoderado', 'documentos')");
    }
};
