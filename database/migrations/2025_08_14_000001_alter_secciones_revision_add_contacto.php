<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar 'contacto' al ENUM de la columna 'seccion'
        DB::statement("ALTER TABLE `secciones_revision` MODIFY COLUMN `seccion` ENUM('datos_generales','actividades','domicilio','constitucion','accionistas','apoderado','archivos','contacto') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quitar 'contacto' del ENUM en reversa
        DB::statement("ALTER TABLE `secciones_revision` MODIFY COLUMN `seccion` ENUM('datos_generales','actividades','domicilio','constitucion','accionistas','apoderado','archivos') NOT NULL");
    }
};


