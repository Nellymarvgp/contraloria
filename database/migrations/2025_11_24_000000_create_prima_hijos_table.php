<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prima_hijos', function (Blueprint $table) {
            $table->id();
            $table->integer('hijos');
            $table->decimal('porcentaje', 5, 2);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prima_hijos');
    }
};
