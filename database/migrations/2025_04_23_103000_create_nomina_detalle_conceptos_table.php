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
        Schema::create('nomina_detalle_conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomina_detalle_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['asignacion', 'deduccion']);
            $table->string('descripcion');
            $table->decimal('monto', 12, 2);
            $table->decimal('porcentaje', 12, 2)->nullable();
            $table->boolean('es_fijo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina_detalle_conceptos');
    }
};
