<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {

        Schema::table('paquete', function (Blueprint $t) {
            if (!Schema::hasColumn('paquete', 'id_solicitud')) {
                $t->integer('id_solicitud')->nullable()->after('id_paquete');
            }

            if (!Schema::hasColumn('paquete', 'categoria')) {
                $t->string('categoria', 255)->nullable()->after('id_solicitud');
            }

            if (!Schema::hasColumn('paquete', 'codigo')) {
                $t->string('codigo', 16)->nullable()->after('categoria');
            }

            if (!Schema::hasColumn('paquete', 'fecha_aprobacion')) {
                $t->date('fecha_aprobacion')->nullable()->after('codigo');
            }

            if (!Schema::hasColumn('paquete', 'fecha_entrega')) {
                $t->date('fecha_entrega')->nullable()->after('fecha_aprobacion');
            }

            if (!Schema::hasColumn('paquete', 'imagen')) {
                $t->string('imagen', 255)->nullable()->after('fecha_entrega');
            }

            if (!Schema::hasColumn('paquete', 'id_encargado')) {
                $t->string('id_encargado', 255)->nullable()->after('imagen');
            }

            if (!Schema::hasColumn('paquete', 'user_id')) {
                $t->unsignedBigInteger('user_id')->nullable()->after('id_encargado');
            }

            if (!Schema::hasColumn('paquete', 'id_ubicacion')) {
                $t->integer('id_ubicacion')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('paquete', 'estado_id')) {
                $t->integer('estado_id')->nullable()->after('cantidad_total');
            }
        });

        // solicitud
        if (!$this->fkExists('paquete', 'paquete_id_solicitud_foreign')) {
            DB::statement("
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'paquete_id_solicitud_foreign'
                          AND table_name = 'paquete'
                    ) THEN
                        ALTER TABLE paquete
                        ADD CONSTRAINT paquete_id_solicitud_foreign
                        FOREIGN KEY (id_solicitud) REFERENCES solicitud (id_solicitud) ON DELETE SET NULL;
                    END IF;
                END$$;
            ");
        }

        // users
        if (!$this->fkExists('paquete', 'paquete_user_id_foreign')) {
            DB::statement("
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'paquete_user_id_foreign'
                          AND table_name = 'paquete'
                    ) THEN
                        ALTER TABLE paquete
                        ADD CONSTRAINT paquete_user_id_foreign
                        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL;
                    END IF;
                END$$;
            ");
        }

        // ubicacion
        if (!$this->fkExists('paquete', 'paquete_id_ubicacion_foreign')) {
            DB::statement("
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'paquete_id_ubicacion_foreign'
                          AND table_name = 'paquete'
                    ) THEN
                        ALTER TABLE paquete
                        ADD CONSTRAINT paquete_id_ubicacion_foreign
                        FOREIGN KEY (id_ubicacion) REFERENCES ubicacion (id_ubicacion) ON DELETE SET NULL;
                    END IF;
                END$$;
            ");
        }

        if (!$this->fkExists('paquete', 'paquete_estado_id_foreign')) {
            DB::statement("
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'paquete_estado_id_foreign'
                          AND table_name = 'paquete'
                    ) THEN
                        ALTER TABLE paquete
                        ADD CONSTRAINT paquete_estado_id_foreign
                        FOREIGN KEY (estado_id) REFERENCES estado (id_estado) ON DELETE SET NULL;
                    END IF;
                END$$;
            ");
        }

        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_indexes WHERE indexname = 'uq_paquete_codigo'
                ) THEN
                    CREATE UNIQUE INDEX uq_paquete_codigo ON paquete (codigo) WHERE codigo IS NOT NULL;
                END IF;
            END$$;
        ");


        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'ci_usuario')) {
                $t->string('ci_usuario', 255)->nullable()->after('id_historial');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'estado')) {
                $t->string('estado', 255)->nullable()->after('ci_usuario');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'imagen_evidencia')) {
                $t->string('imagen_evidencia', 255)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'id_paquete')) {
                $t->integer('id_paquete')->nullable()->after('imagen_evidencia');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'id_ubicacion')) {
                $t->integer('id_ubicacion')->nullable()->after('id_paquete');
            }
            if (!Schema::hasColumn('historial_seguimiento_donaciones', 'fecha_actualizacion')) {
                $t->timestamp('fecha_actualizacion')->nullable()->after('id_ubicacion');
            }
        });

        if (!$this->fkExists('historial_seguimiento_donaciones', 'historial_id_paquete_foreign')) {
            DB::statement("
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'historial_id_paquete_foreign'
                          AND table_name = 'historial_seguimiento_donaciones'
                    ) THEN
                        ALTER TABLE historial_seguimiento_donaciones
                        ADD CONSTRAINT historial_id_paquete_foreign
                        FOREIGN KEY (id_paquete) REFERENCES paquete (id_paquete) ON DELETE SET NULL;
                    END IF;
                END$$;
            ");
        }
        if (!$this->fkExists('historial_seguimiento_donaciones', 'historial_id_ubicacion_foreign')) {
            DB::statement("
                DO $$
                BEGIN
                    IF NOT EXISTS (
                        SELECT 1 FROM information_schema.table_constraints
                        WHERE constraint_name = 'historial_id_ubicacion_foreign'
                          AND table_name = 'historial_seguimiento_donaciones'
                    ) THEN
                        ALTER TABLE historial_seguimiento_donaciones
                        ADD CONSTRAINT historial_id_ubicacion_foreign
                        FOREIGN KEY (id_ubicacion) REFERENCES ubicacion (id_ubicacion) ON DELETE SET NULL;
                    END IF;
                END$$;
            ");
        }

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (Schema::hasColumn('historial_seguimiento_donaciones', 'user_id')) {
                try { $t->dropForeign(['user_id']); } catch (\Throwable $e) {}
                $t->dropColumn('user_id');
            }
            if (Schema::hasColumn('historial_seguimiento_donaciones', 'estado_id')) {
                try { $t->dropForeign(['estado_id']); } catch (\Throwable $e) {}
                $t->dropColumn('estado_id');
            }
        });

        Schema::table('solicitud', function (Blueprint $t) {
            if (!Schema::hasColumn('solicitud', 'fecha_solicitud')) {
                $t->date('fecha_solicitud')->nullable()->after('codigo_seguimiento');
            }
            if (!Schema::hasColumn('solicitud', 'aprobada')) {
                $t->boolean('aprobada')->default(false)->after('fecha_solicitud');
            }
            if (!Schema::hasColumn('solicitud', 'apoyoaceptado')) {
                $t->boolean('apoyoaceptado')->default(false)->after('aprobada');
            }
            if (!Schema::hasColumn('solicitud', 'justificacion')) {
                $t->string('justificacion', 255)->nullable()->after('apoyoaceptado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('paquete', function (Blueprint $t) {
            foreach (['id_solicitud','categoria','codigo','fecha_aprobacion','fecha_entrega','imagen','id_encargado','user_id','id_ubicacion'] as $col) {
                if (Schema::hasColumn('paquete', $col)) {
                    if (in_array($col, ['id_solicitud','user_id','id_ubicacion'])) {
                        try { $t->dropForeign([$col]); } catch (\Throwable $e) {}
                    }
                    $t->dropColumn($col);
                }
            }
        });
        DB::statement("DROP INDEX IF EXISTS uq_paquete_codigo;");

        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            foreach (['ci_usuario','estado','imagen_evidencia','id_paquete','id_ubicacion','fecha_actualizacion'] as $col) {
                if (Schema::hasColumn('historial_seguimiento_donaciones', $col)) {
                    if (in_array($col, ['id_paquete','id_ubicacion'])) {
                        try { $t->dropForeign([$col]); } catch (\Throwable $e) {}
                    }
                    $t->dropColumn($col);
                }
            }
        });

        Schema::table('solicitud', function (Blueprint $t) {
            foreach (['fecha_solicitud','aprobada','apoyoaceptado','justificacion'] as $col) {
                if (Schema::hasColumn('solicitud', $col)) $t->dropColumn($col);
            }
        });
    }

    private function fkExists(string $table, string $fkName): bool
    {
        $exists = DB::selectOne("
            SELECT 1
            FROM information_schema.table_constraints
            WHERE constraint_type = 'FOREIGN KEY'
              AND table_name = ?
              AND constraint_name = ?
        ", [$table, $fkName]);
        return (bool) $exists;
    }
};
