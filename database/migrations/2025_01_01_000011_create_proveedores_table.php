<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users'); 
            $table->string('pv_numero', 20)->unique()->nullable(); 
            $table->string('rfc', 13); 
            $table->enum('tipo_persona', ['Física', 'Moral']);
            $table->enum('estado_padron', ['Activo', 'Inactivo', 'Vencido', 'Pendiente', 'Rechazado'])->default('Pendiente');
            $table->boolean('es_activo')->default(true); 
            $table->date('fecha_alta_padron')->nullable();
            $table->date('fecha_vencimiento_padron')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('razon_social')->nullable(); 
            
            $table->timestamps();
            $table->index(['usuario_id', 'es_activo']);
            $table->index(['estado_padron']);
            $table->index(['fecha_vencimiento_padron']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};