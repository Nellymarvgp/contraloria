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
        Schema::table('remuneracions', function (Blueprint $table) {
            // Modificar los campos de funcionarios para que sean nullable
            $table->unsignedBigInteger('nivel_rango_id')->nullable()->change();
            $table->unsignedBigInteger('grupo_cargo_id')->nullable()->change();
            $table->string('tipo_cargo')->nullable()->change();
            $table->decimal('valor', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remuneracions', function (Blueprint $table) {
            // Revertir los cambios, haciendo que los campos no sean nullable nuevamente
            $table->unsignedBigInteger('nivel_rango_id')->nullable(false)->change();
            $table->unsignedBigInteger('grupo_cargo_id')->nullable(false)->change();
            $table->string('tipo_cargo')->nullable(false)->change();
            $table->decimal('valor', 15, 2)->nullable(false)->change();
        });
    }
};
