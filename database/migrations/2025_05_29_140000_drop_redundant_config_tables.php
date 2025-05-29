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
        // Eliminamos las tablas redundantes ya que toda la funcionalidad
        // ha sido unificada en la tabla deducciones
        
        // Eliminar tabla de configuración de deducciones
        if (Schema::hasTable('deduction_configs')) {
            Schema::dropIfExists('deduction_configs');
        }
        
        // Eliminar tabla de configuración de beneficios
        if (Schema::hasTable('benefit_configs')) {
            Schema::dropIfExists('benefit_configs');
        }
        
        // Eliminar tabla de parámetros de nómina
        if (Schema::hasTable('payroll_parameters')) {
            Schema::dropIfExists('payroll_parameters');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No recreamos las tablas aquí ya que esto requeriría definir toda
        // la estructura de las tablas nuevamente y podría llevar a inconsistencias.
        // En su lugar, se deberían usar las migraciones originales para recrear estas tablas.
    }
};
