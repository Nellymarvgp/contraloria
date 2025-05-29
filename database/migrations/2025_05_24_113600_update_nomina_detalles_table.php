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
        Schema::table('nomina_detalles', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn([
                'salario_base',
                'prima_antiguedad_monto',
                'prima_profesionalizacion_monto',
                'total_asignaciones',
                'total_deducciones',
                'total_neto'
            ]);

            // Add new columns based on the payroll format
            $table->decimal('sueldo_basico', 10, 2)->default(0);
            $table->decimal('prima_profesionalizacion', 10, 2)->default(0);
            $table->decimal('prima_antiguedad', 10, 2)->default(0);
            $table->decimal('prima_por_hijo', 10, 2)->default(0);
            $table->decimal('comida', 10, 2)->default(0);
            $table->decimal('otras_primas', 10, 2)->default(0);
            $table->decimal('ret_ivss', 10, 2)->default(0);
            $table->decimal('ret_pie', 10, 2)->default(0);
            $table->decimal('ret_lph', 10, 2)->default(0);
            $table->decimal('ret_fpj', 10, 2)->default(0);
            $table->decimal('ordinaria', 10, 2)->default(0);
            $table->decimal('incentivo', 10, 2)->default(0);
            $table->decimal('feriado', 10, 2)->default(0);
            $table->decimal('gastos_representacion', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('txt_1', 10, 2)->nullable();
            $table->decimal('txt_2', 10, 2)->nullable();
            $table->decimal('txt_3', 10, 2)->nullable();
            $table->decimal('txt_4', 10, 2)->nullable();
            $table->decimal('txt_5', 10, 2)->nullable();
            $table->decimal('txt_6', 10, 2)->nullable();
            $table->decimal('txt_7', 10, 2)->nullable();
            $table->decimal('txt_8', 10, 2)->nullable();
            $table->decimal('txt_9', 10, 2)->nullable();
            $table->decimal('txt_10', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomina_detalles', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'sueldo_basico',
                'prima_profesionalizacion',
                'prima_antiguedad',
                'prima_por_hijo',
                'comida',
                'otras_primas',
                'ret_ivss',
                'ret_pie',
                'ret_lph',
                'ret_fpj',
                'ordinaria',
                'incentivo',
                'feriado',
                'gastos_representacion',
                'total',
                'txt_1',
                'txt_2',
                'txt_3',
                'txt_4',
                'txt_5',
                'txt_6',
                'txt_7',
                'txt_8',
                'txt_9',
                'txt_10'
            ]);

            // Restore original columns
            $table->decimal('salario_base', 10, 2)->default(0);
            $table->decimal('prima_antiguedad_monto', 10, 2)->default(0);
            $table->decimal('prima_profesionalizacion_monto', 10, 2)->default(0);
            $table->decimal('total_asignaciones', 10, 2)->default(0);
            $table->decimal('total_deducciones', 10, 2)->default(0);
            $table->decimal('total_neto', 10, 2)->default(0);
        });
    }
};
