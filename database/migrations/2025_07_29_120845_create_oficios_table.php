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
        Schema::create('oficios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->string('numero_oficio')->unique();
            $table->date('fecha_oficio');
            $table->string('url_documento')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            $table->index(['tramite_id', 'fecha_oficio']);
            $table->index('numero_oficio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oficios');
    }
};
