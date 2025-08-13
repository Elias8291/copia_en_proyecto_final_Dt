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
        Schema::table('tramites', function (Blueprint $table) {
            // Primero, cambiar el enum para incluir todos los valores necesarios
            $table->enum('status', [
                'Pendiente',
                'Revision_Digital',
                'Revision_Presencial', 
                'Revision_Domiciliaria',
                'Aprobado',
                'Rechazado',
                'Para_Correccion',
                'Cancelado'
            ])->default('Pendiente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            // Revertir a los valores originales
            $table->enum('status', [
                'Pendiente', 
                'Revision_Digital', 
                'Aprobado', 
                'Rechazado', 
                'Para_Correccion', 
                'Cancelado'
            ])->default('Pendiente')->change();
        });
    }
};
