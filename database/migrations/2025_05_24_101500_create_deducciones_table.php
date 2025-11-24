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
        Schema::create('deducciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('tipo', 20)
                ->default('deduccion')
                ->comment('deduccion, beneficio, parametro');
            $table->string('descripcion', 255)->nullable();
            $table->string('campo', 20)
                ->nullable()
                ->comment('txt_1, txt_2, etc. (solo para tipo=parametro)');
            $table->decimal('porcentaje', 8, 2)->nullable();
            $table->boolean('es_fijo')->default(false);
            $table->decimal('monto_fijo', 10, 2)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deducciones');
    }
};
