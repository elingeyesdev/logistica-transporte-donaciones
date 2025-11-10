<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1)  FK columns
        Schema::table('solicitud', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitud', 'id_solicitante')) {
                $table->integer('id_solicitante')->nullable()->after('id_solicitud');
            }
            if (!Schema::hasColumn('solicitud', 'id_destino')) {
                $table->integer('id_destino')->nullable()->after('id_solicitante'); 
            }
        });

        // 2) FKs 
        Schema::table('solicitud', function (Blueprint $table) {
            if (Schema::hasColumn('solicitud', 'id_solicitante')) {
                try { $table->foreign('id_solicitante')->references('id_solicitante')->on('solicitante')->nullOnDelete(); } catch (\Throwable $e) {}
            }
            if (Schema::hasColumn('solicitud', 'id_destino')) {
                try { $table->foreign('id_destino')->references('id_destino')->on('destino')->nullOnDelete(); } catch (\Throwable $e) {}
            }
        });

        Schema::table('solicitud', function (Blueprint $table) {
            foreach ([
                'nombre','apellido','carnet_identidad','correo_electronico','nro_celular',
                'comunidad_solicitante','provincia','ubicacion',
                'nombre_solicitante','descripcion','fecha_creacion',
            ] as $col) {
                if (Schema::hasColumn('solicitud', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            foreach ([
                'nombre','apellido','carnet_identidad','correo_electronico','nro_celular',
                'comunidad_solicitante','provincia','ubicacion',
                'nombre_solicitante','descripcion','fecha_creacion',
            ] as $col) {
                if (!Schema::hasColumn('solicitud', $col)) {
                    $type = in_array($col, ['fecha_creacion']) ? 'date' : 'string';
                    $type === 'date' ? $table->date($col)->nullable() : $table->string($col)->nullable();
                }
            }

            try { $table->dropForeign(['id_solicitante']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['id_destino']); } catch (\Throwable $e) {}
        });

        Schema::table('solicitud', function (Blueprint $table) {
            if (Schema::hasColumn('solicitud','id_solicitante')) $table->dropColumn('id_solicitante');
            if (Schema::hasColumn('solicitud','id_destino'))     $table->dropColumn('id_destino');
        });
    }
};
