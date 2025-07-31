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
        Schema::table('citas', function (Blueprint $table) {
            $table->unsignedTinyInteger('contador_reagendamientos')->default(0)->after('estado');
            $table->unsignedTinyInteger('max_reagendamientos')->default(2)->after('contador_reagendamientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['contador_reagendamientos', 'max_reagendamientos']);
        });
    }
};
