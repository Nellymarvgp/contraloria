<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grupo_cargos', function (Blueprint $table) {
            $table->enum('categoria', [
                'administrativo_bachiller',
                'tecnico_superior',
                'profesional_universitario'
            ])->nullable()->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('grupo_cargos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
