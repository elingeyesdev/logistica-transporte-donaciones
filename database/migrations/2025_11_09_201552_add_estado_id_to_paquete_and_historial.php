<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('paquete', function (Blueprint $t) {
            if (!Schema::hasColumn('paquete','estado_id')) {
                $t->integer('estado_id')->nullable()->after('cantidad_total');
                $t->foreign('estado_id')->references('id_estado')->on('estado')->nullOnDelete();
            }
        });

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones','estado_id')) {
                $t->integer('estado_id')->nullable()->after('ci_usuario');
                $t->foreign('estado_id')->references('id_estado')->on('estado')->nullOnDelete();
            }
        });

        DB::statement("
            UPDATE paquete p
            SET estado_id = e.id_estado
            FROM estado e
            WHERE p.estado_entrega IS NOT NULL
              AND trim(lower(p.estado_entrega)) = trim(lower(e.nombre_estado))
        ");

        DB::statement("
            UPDATE historial_seguimiento_donaciones h
            SET estado_id = e.id_estado
            FROM estado e
            WHERE h.estado IS NOT NULL
              AND trim(lower(h.estado)) = trim(lower(e.nombre_estado))
        ");

        Schema::table('paquete', function (Blueprint $t) {
            if (Schema::hasColumn('paquete','estado_entrega')) $t->dropColumn('estado_entrega');
        });
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (Schema::hasColumn('historial_seguimiento_donaciones','estado')) $t->dropColumn('estado');
        });
    }

    public function down(): void
    {
        Schema::table('paquete', function (Blueprint $t) {
            if (!Schema::hasColumn('paquete','estado_entrega')) $t->string('estado_entrega')->nullable();
            try { $t->dropForeign(['estado_id']); } catch (\Throwable $e) {}
            $t->dropColumn('estado_id');
        });

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones','estado')) $t->string('estado')->nullable();
            try { $t->dropForeign(['estado_id']); } catch (\Throwable $e) {}
            $t->dropColumn('estado_id');
        });
    }
};
