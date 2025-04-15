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
        Schema::table('empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('prima_antiguedad_id')->nullable();
            $table->unsignedBigInteger('prima_profesionalizacion_id')->nullable();
            $table->unsignedBigInteger('nivel_rango_id')->nullable();
            $table->unsignedBigInteger('grupo_cargo_id')->nullable();
            $table->enum('tipo_cargo', ['administrativo', 'tecnico_superior', 'profesional_universitario'])->nullable();
            
            $table->foreign('prima_antiguedad_id')->references('id')->on('prima_antiguedads');
            $table->foreign('prima_profesionalizacion_id')->references('id')->on('prima_profesionalizacions');
            $table->foreign('nivel_rango_id')->references('id')->on('nivel_rangos');
            $table->foreign('grupo_cargo_id')->references('id')->on('grupo_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['prima_antiguedad_id']);
            $table->dropForeign(['prima_profesionalizacion_id']);
            $table->dropForeign(['nivel_rango_id']);
            $table->dropForeign(['grupo_cargo_id']);
            
            $table->dropColumn('prima_antiguedad_id');
            $table->dropColumn('prima_profesionalizacion_id');
            $table->dropColumn('nivel_rango_id');
            $table->dropColumn('grupo_cargo_id');
            $table->dropColumn('tipo_cargo');
        });
    }
};
