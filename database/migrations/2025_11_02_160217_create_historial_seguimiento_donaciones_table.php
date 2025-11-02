<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reporte', function (Blueprint $table) {
            $table->increments('id_reporte');
            $table->string('direccion_archivo', 255)->nullable();
            $table->date('fecha_reporte')->nullable();
            $table->string('gestion', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reporte');
    }
};
