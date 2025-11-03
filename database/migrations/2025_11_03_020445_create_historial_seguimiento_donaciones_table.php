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
        Schema::create('historial_seguimiento_donaciones', function (Blueprint $table) {
               $table->increments('id_historial');
            $table->string('ci_usuario', 255)->nullable();
            $table->string('estado', 255)->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->string('imagen_evidencia', 255)->nullable();

            $table->integer('id_donacion')->nullable()->index();
            $table->integer('id_ubicacion')->nullable()->index();

            $table->timestamps();
            $table->foreign('id_donacion')->references('id_donacion')->on('donacion');
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicacion');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
            try { $table->dropForeign(['id_donacion']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['id_ubicacion']); } catch (\Throwable $e) {}
        });
        Schema::dropIfExists('historial_seguimiento_donaciones');
    }
};
