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

        Schema::table('solicitud', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipoemergencia')
                  ->nullable()
                  ->after('tipo_emergencia');

            $table->foreign('id_tipoemergencia')
                  ->references('id_emergencia')
                  ->on('tipo_emergencia')
                  ->onDelete('set null');
        });

        Schema::table('vehiculo', function (Blueprint $table) {
            $table->unsignedBigInteger('id_marca')
                  ->nullable()
                  ->after('id_tipovehiculo');

            $table->foreign('id_marca')
                  ->references('id_marca')
                  ->on('marca')
                  ->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')
                  ->nullable()
                  ->after('activo');

            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('rol')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // solicitud
        Schema::table('solicitud', function (Blueprint $table) {
            if (Schema::hasColumn('solicitud', 'id_tipoemergencia')) {
                try { $table->dropForeign(['id_tipoemergencia']); } catch (\Throwable $e) {}
                $table->dropColumn('id_tipoemergencia');
            }
        });

        // vehiculo
        Schema::table('vehiculo', function (Blueprint $table) {
            if (Schema::hasColumn('vehiculo', 'id_marca')) {
                try { $table->dropForeign(['id_marca']); } catch (\Throwable $e) {}
                $table->dropColumn('id_marca');
            }
        });

        // users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id_rol')) {
                try { $table->dropForeign(['id_rol']); } catch (\Throwable $e) {}
                $table->dropColumn('id_rol');
            }
        });
    }
};
