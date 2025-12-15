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
        Schema::create('destino', function (Blueprint $table) {
            $table->timestamps();
            $table->increments('id_destino');
            $table->string('comunidad', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->float('latitud')->nullable();
            $table->float('longitud')->nullable();
            $table->string('provincia', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destino');
    }
};
