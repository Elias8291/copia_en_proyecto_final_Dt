<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revision_secciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tramite_id');
            $table->string('seccion', 50);
            $table->text('comentario')->nullable();
            $table->boolean('aprobado')->nullable(); // null = pendiente, true = aprobado, false = rechazado
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('tramite_id')->references('id')->on('tramites')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Descomenta si quieres la relación
        });
    }

    public function down()
    {
        Schema::dropIfExists('revision_secciones');
    }
}; 