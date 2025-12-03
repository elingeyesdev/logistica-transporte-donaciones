<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reporte', function (Blueprint $table) {
            if (Schema::hasColumn('reporte', 'direccion_archivo')) {
                $table->dropColumn('direccion_archivo');
            }

            if (!Schema::hasColumn('reporte', 'nombre_pdf')) {
                $table->string('nombre_pdf', 255)->nullable()->after('id_paquete');
            }

            if (!Schema::hasColumn('reporte', 'ruta_pdf')) {
                $table->string('ruta_pdf', 255)->nullable()->after('nombre_pdf');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reporte', function (Blueprint $table) {
            if (!Schema::hasColumn('reporte', 'direccion_archivo')) {
                $table->string('direccion_archivo', 255)->nullable()->after('id_paquete');
            }

            foreach (['ruta_pdf', 'nombre_pdf'] as $column) {
                if (Schema::hasColumn('reporte', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
