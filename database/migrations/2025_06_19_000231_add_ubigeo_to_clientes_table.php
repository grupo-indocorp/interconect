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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('departamento_codigo', 2)->after('etapa_id');
            $table->string('provincia_codigo', 4)->after('etapa_id');
            $table->string('distrito_codigo', 6)->after('etapa_id');
            $table->foreign('departamento_codigo')->references('codigo')->on('departamentos');
            $table->foreign('provincia_codigo')->references('codigo')->on('provincias');
            $table->foreign('distrito_codigo')->references('codigo')->on('distritos');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['distrito_codigo']);
            $table->dropForeign(['provincia_codigo']);
            $table->dropForeign(['departamento_codigo']);
            $table->dropColumn(['distrito_codigo', 'provincia_codigo', 'departamento_codigo']);
        });
    }
};
