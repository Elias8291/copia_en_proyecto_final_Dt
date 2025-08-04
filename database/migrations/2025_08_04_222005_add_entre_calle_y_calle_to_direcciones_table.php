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
        Schema::table('direcciones', function (Blueprint $table) {
            $table->string('entre_calle', 255)->nullable()->after('calle'); // Primera calle de referencia
            $table->string('y_calle', 255)->nullable()->after('entre_calle'); // Segunda calle de referencia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones', function (Blueprint $table) {
            $table->dropColumn(['entre_calle', 'y_calle']);
        });
    }
};
