<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reporte', function (Blueprint $t) {
            if (!Schema::hasColumn('reporte','id_paquete')) {
                $t->integer('id_paquete')->nullable()->after('id_reporte');
                $t->foreign('id_paquete')->references('id_paquete')->on('paquete')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reporte', function (Blueprint $t) {
            try { $t->dropForeign(['id_paquete']); } catch (\Throwable $e) {}
            $t->dropColumn('id_paquete');
        });
    }
};
