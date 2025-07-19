<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pais_id')->unsigned();
            $table->string('nombre', 370);
            $table->timestamps();

            $table->foreign('pais_id')->references('id')->on('paises')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('estados');
    }
};