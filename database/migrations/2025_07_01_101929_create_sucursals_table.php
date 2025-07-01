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
        Schema::create('sucursals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->boolean('facilidad_tecnica')->default(false);
            $table->string('departamento_codigo', 2);
            $table->string('provincia_codigo', 4);
            $table->string('distrito_codigo', 6);
            $table->foreignId('cliente_id')->constrained();
            $table->foreign('departamento_codigo')->references('codigo')->on('departamentos');
            $table->foreign('provincia_codigo')->references('codigo')->on('provincias');
            $table->foreign('distrito_codigo')->references('codigo')->on('distritos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursals');
    }
};
