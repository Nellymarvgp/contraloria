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
        Schema::table('deducciones', function (Blueprint $table) {
            // Añadir campo tipo para distinguir entre deducciones, beneficios y parámetros
            $table->string('tipo', 20)
                ->default('deduccion')
                ->comment('deduccion, beneficio, parametro')
                ->after('nombre');
            
            // Añadir campo para TXT1-TXT10 (solo para tipo=parametro)
            $table->string('campo', 20)
                ->nullable()
                ->comment('txt_1, txt_2, etc. (solo para tipo=parametro)')
                ->after('descripcion');
                
            // Asegurarse de que la descripción sea nullable
            $table->string('descripcion', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deducciones', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('campo');
        });
    }
};
