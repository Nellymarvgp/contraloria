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
        Schema::create('beneficios_cargo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficio_id')
                  ->constrained('beneficios')
                  ->onDelete('cascade');
            $table->string('cargo');
            $table->decimal('porcentaje', 10, 2)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficios_cargo');
    }
};
