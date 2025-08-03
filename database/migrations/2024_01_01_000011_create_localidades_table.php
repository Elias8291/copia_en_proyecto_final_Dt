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
        Schema::create('localidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            $table->timestamps();
        });

        // Add foreign key constraint for asentamientos table
        Schema::table('asentamientos', function (Blueprint $table) {
            $table->foreign('localidad_id')->references('id')->on('localidades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asentamientos', function (Blueprint $table) {
            $table->dropForeign(['localidad_id']);
        });
        
        Schema::dropIfExists('localidades');
    }
}; 