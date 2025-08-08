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
        Schema::table('revisiones_tramite', function (Blueprint $table) {
            $table->foreignId('revisor_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisiones_tramite', function (Blueprint $table) {
            $table->foreignId('revisor_id')->nullable(false)->change();
        });
    }
};
