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
            $table->enum('tipo_personal', ['obreros', 'administracion_publica'])->nullable()->after('tipo_cargo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remuneracions', function (Blueprint $table) {
            $table->dropColumn('tipo_personal');
        });
    }
};
