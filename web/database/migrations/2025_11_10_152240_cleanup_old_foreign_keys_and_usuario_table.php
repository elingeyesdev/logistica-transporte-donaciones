<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
            try {
                $table->dropForeign('historial_id_paquete_foreign');
            } catch (\Throwable $e) {
                //
            }

            try {
                $table->dropForeign('historial_id_ubicacion_foreign');
            } catch (\Throwable $e) {
                // 
            }
        });

        Schema::table('paquete', function (Blueprint $table) {
            try {
                $table->dropForeign('donacion_id_solicitud_foreign');
            } catch (\Throwable $e) {
                //
            }

            try {
                $table->dropForeign('paquete_id_ubicacion_foreign');
            } catch (\Throwable $e) {
                //
            }

            try {
                $table->dropForeign('paquete_user_id_foreign');
            } catch (\Throwable $e) {
                //
            }
        });

        Schema::dropIfExists('usuario');
    }

    public function down(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('contrasena')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
};
