<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->boolean('tiene_hijos')->default(false)->after('fecha_ingreso');
            $table->unsignedTinyInteger('cantidad_hijos')->nullable()->after('tiene_hijos');
        });
    }
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn(['tiene_hijos', 'cantidad_hijos']);
        });
    }
};
