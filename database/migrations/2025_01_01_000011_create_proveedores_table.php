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
            $table->foreignId('usuario_id')->constrained('users'); // Un usuario puede tener múltiples proveedores
            $table->string('pv_numero', 20)->unique()->nullable(); // Número de padrón único cuando se asigna
            $table->string('rfc', 13); // RFC sin unique porque puede repetirse entre proveedores del mismo usuario
            $table->enum('tipo_persona', ['Física', 'Moral']);
            $table->enum('estado_padron', ['Activo', 'Inactivo', 'Vencido', 'Pendiente', 'Rechazado'])->default('Pendiente');
            $table->boolean('es_activo')->default(true); // Indica si es el proveedor activo del usuario
            $table->date('fecha_alta_padron')->nullable();
            $table->date('fecha_vencimiento_padron')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('razon_social')->nullable(); // Nombre completo o razón social
            
            $table->timestamps();
            
            // Índices para optimizar consultas
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