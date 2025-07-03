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
        Schema::table('producto_venta', function (Blueprint $table) {
            $table->string('sucursal_nombre')->after('total')->nullable();
            $table->foreignId('sucursal_id')->after('venta_id')->nullable()->constrained('sucursals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_venta', function (Blueprint $table) {
            $table->dropColumn('sucursal_nombre');
            $table->dropForeign(['sucursal_id']);   
            $table->dropColumn('sucursal_id'); 
        });
    }
};
