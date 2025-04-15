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
        Schema::create('remuneracions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nivel_rango_id');
            $table->unsignedBigInteger('grupo_cargo_id');
            $table->enum('tipo_cargo', ['administrativo', 'tecnico_superior', 'profesional_universitario']);
            $table->decimal('valor', 12, 2);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('nivel_rango_id')->references('id')->on('nivel_rangos');
            $table->foreign('grupo_cargo_id')->references('id')->on('grupo_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remuneracions');
    }
};
