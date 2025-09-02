<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clientetipos', function (Blueprint $table) {
            $table->integer('estado')->default(1)->after('nombre');
        });
        DB::table('clientetipos')->update(['estado' => 0]);
        DB::table('clientetipos')->insert([
            'nombre' => 'Solicitar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('clientetipos')->insert([
            'nombre' => 'Bloqueado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('clientetipos')->insert([
            'nombre' => 'Bloqueado Consultar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientetipos', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
