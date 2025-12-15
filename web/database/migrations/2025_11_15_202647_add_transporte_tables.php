<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        if (!Schema::hasTable('tipo_licencia')) {
            Schema::create('tipo_licencia', function (Blueprint $t) {
                $t->increments('id_licencia');
                $t->string('licencia', 100);
            });
        }

        if (!Schema::hasTable('conductor')) {
            Schema::create('conductor', function (Blueprint $t) {
                $t->increments('conductor_id');
                $t->string('nombre', 255);
                $t->string('apellido', 255);
                $t->date('fecha_nacimiento');
                $t->string('ci', 50);
                $t->string('celular', 20);
                $t->integer('id_licencia')->unsigned();

                $t->foreign('id_licencia')
                  ->references('id_licencia')
                  ->on('tipo_licencia')
                  ->onDelete('set null');
            });
        }

        if (!Schema::hasTable('tipo_vehiculo')) {
            Schema::create('tipo_vehiculo', function (Blueprint $t) {
                $t->increments('id_tipovehiculo');
                $t->string('nombre_tipo_vehiculo', 100);
            });
        }

        if (!Schema::hasTable('vehiculo')) {
            Schema::create('vehiculo', function (Blueprint $t) {
                $t->increments('id_vehiculo');
                $t->string('placa', 50)->unique();
                $t->string('capacidad_aproximada', 50)->nullable();
                $t->integer('id_tipovehiculo')->unsigned()->nullable();
                $t->string('modelo_anio', 10)->nullable();
                $t->string('modelo', 100)->nullable();

                $t->foreign('id_tipovehiculo')
                  ->references('id_tipovehiculo')
                  ->on('tipo_vehiculo')
                  ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('vehiculo');
        Schema::dropIfExists('tipo_vehiculo');
        Schema::dropIfExists('conductor');
        Schema::dropIfExists('tipo_licencia');

        Schema::enableForeignKeyConstraints();
    }
};
