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
        Schema::create('solicitante', function (Blueprint $table) {
            $table->increments('id_solicitante');
            $table->string('apellido', 255)->nullable();
            $table->string('ci', 255)->nullable()->unique();
            $table->string('email', 255)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('telefono', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitante');
    }
};
