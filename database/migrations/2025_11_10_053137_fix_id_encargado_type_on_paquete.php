<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $fkNames = DB::select(<<<SQL
            SELECT c.conname AS name
            FROM pg_constraint c
            JOIN pg_class t       ON t.oid = c.conrelid
            JOIN unnest(c.conkey) WITH ORDINALITY AS cols(attnum, ord) ON true
            JOIN pg_attribute a   ON a.attrelid = t.oid AND a.attnum = cols.attnum
            WHERE c.contype = 'f'
              AND t.relname = 'paquete'
              AND a.attname = 'id_encargado';
        SQL);

        foreach ($fkNames as $fk) {
            DB::statement('ALTER TABLE paquete DROP CONSTRAINT "' . $fk->name . '"');
        }

        if (Schema::hasColumn('paquete', 'id_encargado')) {
            Schema::table('paquete', function (Blueprint $t) {
                $t->string('id_encargado')->nullable()->change();
            });
        } else {
            Schema::table('paquete', function (Blueprint $t) {
                $t->string('id_encargado')->nullable()->after('descripcion');
            });
        }
        $alreadyHasFk = DB::select(<<<SQL
            SELECT 1
            FROM pg_constraint c
            JOIN pg_class t ON t.oid = c.conrelid
            WHERE c.contype='f' AND t.relname='paquete'
              AND c.conname = 'paquete_id_encargado_foreign';
        SQL);

        if (empty($alreadyHasFk)) {
            Schema::table('paquete', function (Blueprint $t) {
                $t->foreign('id_encargado', 'paquete_id_encargado_foreign')
                  ->references('ci')->on('users')
                  ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $fkNames = DB::select(<<<SQL
            SELECT c.conname AS name
            FROM pg_constraint c
            JOIN pg_class t       ON t.oid = c.conrelid
            JOIN unnest(c.conkey) WITH ORDINALITY AS cols(attnum, ord) ON true
            JOIN pg_attribute a   ON a.attrelid = t.oid AND a.attnum = cols.attnum
            WHERE c.contype = 'f'
              AND t.relname = 'paquete'
              AND a.attname = 'id_encargado';
        SQL);

        foreach ($fkNames as $fk) {
            DB::statement('ALTER TABLE paquete DROP CONSTRAINT "' . $fk->name . '"');
        }
    }
};
