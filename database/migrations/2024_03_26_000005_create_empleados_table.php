<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->unique();
            $table->foreign('cedula')->references('cedula')->on('users');
            $table->foreignId('cargo_id')->constrained('cargos');
            $table->foreignId('departamento_id')->constrained('departamentos');
            $table->foreignId('horario_id')->constrained('horarios');
            $table->foreignId('estado_id')->constrained('estados');
            $table->decimal('salario', 10, 2);
            $table->date('fecha_ingreso');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
