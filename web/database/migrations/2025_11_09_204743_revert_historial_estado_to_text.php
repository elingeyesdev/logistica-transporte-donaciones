<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'estado')) {
                $t->string('estado')->nullable()->after('ci_usuario');
            }
        });

        DB::statement("
            UPDATE historial_seguimiento_donaciones h
            SET estado = e.nombre_estado
            FROM paquete p
            JOIN estado e ON e.id_estado = p.estado_id
            WHERE h.id_paquete = p.id_paquete
              AND h.estado IS NULL
        ");

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            try { $t->dropForeign(['estado_id']); } catch (\Throwable $e) {}
            if (Schema::hasColumn('historial_seguimiento_donaciones', 'estado_id')) {
                $t->dropColumn('estado_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'estado_id')) {
                $t->integer('estado_id')->nullable()->after('ci_usuario');
            }
        });

        DB::statement("
            UPDATE historial_seguimiento_donaciones h
            SET estado_id = e.id_estado
            FROM estado e
            WHERE h.estado IS NOT NULL
              AND trim(lower(h.estado)) = trim(lower(e.nombre_estado))
        ");

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            $t->foreign('estado_id')->references('id_estado')->on('estado')->nullOnDelete();
        });
    }
};
