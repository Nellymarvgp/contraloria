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
            $table->unsignedBigInteger('nivel_rango_id')->nullable();
            $table->unsignedBigInteger('grupo_cargo_id')->nullable();
            $table->string('tipo_cargo')->nullable();
            $table->enum('tipo_personal', ['obreros', 'administracion_publica'])->nullable();
            $table->enum('clasificacion', ['no_calificados', 'calificados', 'supervisor'])->nullable();
            $table->tinyInteger('grado')->nullable();
            $table->decimal('valor', 15, 2)->nullable();
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
