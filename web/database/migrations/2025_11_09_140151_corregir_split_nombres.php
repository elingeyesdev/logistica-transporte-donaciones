<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
     public function up(): void
    {
        if (DB::getSchemaBuilder()->hasColumn('users','name')) {
            DB::statement("
                UPDATE users
                SET nombre   = COALESCE(NULLIF(trim(split_part(name, ' ', 1)), ''), nombre),
                    apellido = COALESCE(NULLIF(trim(regexp_replace(name, '^[^ ]+ ?', '')), ''), apellido)
                WHERE nombre IS NULL OR apellido IS NULL
            ");
        }

        if (DB::getSchemaBuilder()->hasTable('usuario')) {
            // match by correo/email first
            DB::statement("
                UPDATE users u
                SET
                    ci = COALESCE(u.ci, us.ci),
                    telefono = COALESCE(u.telefono, us.telefono),
                    administrador = COALESCE(u.administrador, us.admin),
                    activo = COALESCE(u.activo, us.active)
                FROM usuario us
                WHERE (u.correo_electronico = us.correo_electronico)
            ");

            DB::statement("
                UPDATE users u
                SET
                    nombre = COALESCE(u.nombre, us.nombre),
                    apellido = COALESCE(u.apellido, us.apellido),
                    ci = COALESCE(u.ci, us.ci),
                    telefono = COALESCE(u.telefono, us.telefono)
                FROM usuario us
                WHERE u.ci IS NOT DISTINCT FROM us.ci
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
