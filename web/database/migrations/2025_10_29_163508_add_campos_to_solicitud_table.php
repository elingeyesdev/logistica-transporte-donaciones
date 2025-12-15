<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('carnet_identidad')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('comunidad_solicitante')->nullable();
           // $table->string('ubicacion')->nullable();
            $table->string('provincia')->nullable();
            $table->string('nro_celular')->nullable();
            $table->integer('cantidad_personas')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('tipo_emergencia')->nullable();
            $table->text('insumos_necesarios')->nullable();
           // $table->string('codigo_seguimiento')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'apellido',
                'carnet_identidad',
                'correo_electronico',
                'comunidad_solicitante',
               // 'ubicacion',
                'provincia',
                'nro_celular',
                'cantidad_personas',
                'fecha_inicio',
                'tipo_emergencia',
                'insumos_necesarios',
               // 'codigo_seguimiento',
            ]);
        });
    }
};
