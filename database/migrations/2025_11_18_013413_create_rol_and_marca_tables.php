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
        // Tabla ROL
        Schema::create('rol', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('titulo_rol', 255);
        });

        // Tabla MARCA
        Schema::create('marca', function (Blueprint $table) {
            $table->id('id_marca'); 
            $table->string('nombre_marca', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marca');
        Schema::dropIfExists('rol');
    }
};
