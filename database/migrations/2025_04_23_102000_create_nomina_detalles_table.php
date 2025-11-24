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
            $table->decimal('sueldo_basico', 10, 2)->default(0);
            $table->decimal('prima_profesionalizacion', 10, 2)->default(0);
            $table->decimal('prima_antiguedad', 10, 2)->default(0);
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
