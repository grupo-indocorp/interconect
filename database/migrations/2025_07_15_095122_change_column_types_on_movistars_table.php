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
        Schema::table('movistars', function (Blueprint $table) {
            $table->integer('linea_claro')->change();
            $table->integer('linea_entel')->change();
            $table->integer('linea_bitel')->change();
            $table->integer('linea_movistar')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movistars', function (Blueprint $table) {
            $table->char('linea_claro', 3)->change();
            $table->char('linea_entel', 3)->change();
            $table->char('linea_bitel', 3)->change();
            $table->char('linea_movistar', 3)->change();
        });
    }
};
