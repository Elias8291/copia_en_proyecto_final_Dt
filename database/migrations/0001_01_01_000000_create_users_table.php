<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->timestamp('fecha_verificacion_correo')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('rfc', 13);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->enum('estado', ['pendiente', 'activo', 'inactivo', 'suspendido'])->default('pendiente');
            $table->string('verification_token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};