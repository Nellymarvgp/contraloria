<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nomina_detalles', function (Blueprint $table) {
            if (!Schema::hasColumn('nomina_detalles', 'prima_por_hijo')) {
                $table->decimal('prima_por_hijo', 10, 2)->default(0)->after('prima_antiguedad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nomina_detalles', function (Blueprint $table) {
            if (Schema::hasColumn('nomina_detalles', 'prima_por_hijo')) {
                $table->dropColumn('prima_por_hijo');
            }
        });
    }
};
