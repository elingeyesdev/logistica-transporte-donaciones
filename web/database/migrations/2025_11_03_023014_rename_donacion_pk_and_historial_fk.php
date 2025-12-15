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
        if (Schema::hasTable('historial_seguimiento_donaciones')) {
            Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
                if (Schema::hasColumn('historial_seguimiento_donaciones','id_donacion')) {
                    try { $table->dropForeign(['id_donacion']); } catch (\Throwable $e) {}
                }
            });
        }
          if (Schema::hasTable('paquete')) {
            Schema::table('paquete', function (Blueprint $table) {
                if (Schema::hasColumn('paquete','id_donacion') && !Schema::hasColumn('paquete','id_paquete')) {
                    $table->renameColumn('id_donacion', 'id_paquete');
                }
            });
        }
           if (Schema::hasTable('historial_seguimiento_donaciones')) {
            Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
                if (Schema::hasColumn('historial_seguimiento_donaciones','id_donacion') && !Schema::hasColumn('historial_seguimiento_donaciones','id_paquete')) {
                    $table->renameColumn('id_donacion', 'id_paquete'); 
                }
            });

            Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
                if (Schema::hasColumn('historial_seguimiento_donaciones','id_paquete')) {
                    try { $table->index('id_paquete'); } catch (\Throwable $e) {}
                    $table->foreign('id_paquete')
                          ->references('id_paquete')->on('paquete');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         if (Schema::hasTable('historial_seguimiento_donaciones')) {
            Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
                try { $table->dropForeign(['id_paquete']); } catch (\Throwable $e) {}
            });
        }

         if (Schema::hasTable('paquete')) {
            Schema::table('paquete', function (Blueprint $table) {
                if (Schema::hasColumn('paquete','id_paquete') && !Schema::hasColumn('paquete','id_donacion')) {
                    $table->renameColumn('id_paquete', 'id_donacion');
                }
            });
        }
        if (Schema::hasTable('historial_seguimiento_donaciones')) {
            Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
                if (Schema::hasColumn('historial_seguimiento_donaciones','id_paquete') && !Schema::hasColumn('historial_seguimiento_donaciones','id_donacion')) {
                    $table->renameColumn('id_paquete', 'id_donacion');
                }
                if (Schema::hasColumn('historial_seguimiento_donaciones','id_donacion')) {
                    $table->foreign('id_donacion')
                          ->references('id_donacion')->on('donacion');
                }
            });
        }
    }
};
