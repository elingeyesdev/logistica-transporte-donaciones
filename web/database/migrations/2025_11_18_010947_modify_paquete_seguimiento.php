<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paquete', function (Blueprint $table) {
            if (!Schema::hasColumn('paquete', 'id_conductor')) {
                $table->integer('id_conductor')->nullable(); 
            }

            if (!Schema::hasColumn('paquete', 'id_vehiculo')) {
                $table->integer('id_vehiculo')->nullable(); 
            }
        });

        Schema::table('paquete', function (Blueprint $table) {
            $table->foreign('id_conductor')
                ->references('conductor_id')
                ->on('conductor')
                ->onDelete('set null');

            $table->foreign('id_vehiculo')
                ->references('id_vehiculo')
                ->on('vehiculo')
                ->onDelete('set null');
        });

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'conductor_nombre')) {
                $table->string('conductor_nombre', 255)->nullable()->after('ci_usuario');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'conductor_ci')) {
                $table->string('conductor_ci', 50)->nullable()->after('conductor_nombre');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'vehiculo_placa')) {
                $table->string('vehiculo_placa', 50)->nullable()->after('conductor_ci');
            }
        });
    }

    public function down(): void
    {
        Schema::table('paquete', function (Blueprint $table) {
            if (Schema::hasColumn('paquete', 'id_conductor')) {
                try { $table->dropForeign(['id_conductor']); } catch (\Throwable $e) {}
                $table->dropColumn('id_conductor');
            }
            if (Schema::hasColumn('paquete', 'id_vehiculo')) {
                try { $table->dropForeign(['id_vehiculo']); } catch (\Throwable $e) {}
                $table->dropColumn('id_vehiculo');
            }
        });

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
            foreach (['conductor_nombre', 'conductor_ci', 'vehiculo_placa'] as $col) {
                if (Schema::hasColumn('historial_seguimiento_donaciones', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
