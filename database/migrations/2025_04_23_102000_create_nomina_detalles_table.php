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
        Schema::create('nomina_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomina_id')->constrained()->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained();
            $table->decimal('salario_base', 12, 2);
            $table->decimal('prima_antiguedad_monto', 12, 2)->default(0);
            $table->decimal('prima_profesionalizacion_monto', 12, 2)->default(0);
            $table->decimal('total_asignaciones', 12, 2)->default(0);
            $table->decimal('total_deducciones', 12, 2)->default(0);
            $table->decimal('total_neto', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina_detalles');
    }
};
