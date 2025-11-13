<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('remuneracions', function (Blueprint $table) {
            $table->enum('clasificacion', ['no_calificados', 'calificados', 'supervisor'])->nullable()->after('tipo_personal');
            $table->tinyInteger('grado')->nullable()->after('clasificacion');
            $table->decimal('valor_minimo', 15, 2)->nullable()->after('grado');
            $table->decimal('valor_maximo', 15, 2)->nullable()->after('valor_minimo');
        });
    }

    public function down()
    {
        Schema::table('remuneracions', function (Blueprint $table) {
            $table->dropColumn(['clasificacion', 'grado', 'valor_minimo', 'valor_maximo']);
        });
    }
};
