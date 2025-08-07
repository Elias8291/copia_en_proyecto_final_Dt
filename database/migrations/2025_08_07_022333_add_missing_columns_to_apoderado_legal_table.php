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
        Schema::table('apoderado_legal', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('apoderado_legal', 'numero_escritura_constitutiva_poder')) {
                $table->string('numero_escritura_constitutiva_poder', 255)->nullable();
            }
            
            if (!Schema::hasColumn('apoderado_legal', 'numero_registro_publico_poder')) {
                $table->string('numero_registro_publico_poder', 255)->nullable();
            }
            
            if (!Schema::hasColumn('apoderado_legal', 'fecha_inscripcion_poder')) {
                $table->date('fecha_inscripcion_poder')->nullable();
            }
            
            if (!Schema::hasColumn('apoderado_legal', 'instrumento_notarial_id')) {
                $table->foreignId('instrumento_notarial_id')->constrained('instrumentos_notariales')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('apoderado_legal', 'tramite_id')) {
                $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('apoderado_legal', 'proveedor_id')) {
                $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('apoderado_legal', 'status')) {
                $table->enum('status', ['pendiente', 'vigente', 'historico', 'rechazado'])->default('pendiente');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apoderado_legal', function (Blueprint $table) {
            $table->dropColumn([
                'numero_escritura_constitutiva_poder',
                'numero_registro_publico_poder',
                'fecha_inscripcion_poder',
                'instrumento_notarial_id',
                'tramite_id',
                'proveedor_id',
                'status'
            ]);
        });
    }
};
