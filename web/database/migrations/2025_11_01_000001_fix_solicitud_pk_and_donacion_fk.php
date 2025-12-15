<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('solicitud')) {
            if (Schema::hasColumn('solicitud', 'id') && !Schema::hasColumn('solicitud', 'id_solicitud')) {
                Schema::table('solicitud', function (Blueprint $table) {
                    $table->renameColumn('id', 'id_solicitud'); // cambio de nombre para que coincida en fk
                });
            }
        }

        if (Schema::hasTable('donacion')) {
            Schema::table('donacion', function (Blueprint $table) {
                if (Schema::hasColumn('donacion', 'id_solicitud')) {
                    try { $table->dropForeign(['id_solicitud']); } catch (\Throwable $e) {  }
                }
            });

            Schema::table('donacion', function (Blueprint $table) {
                if (Schema::hasColumn('donacion', 'id_solicitud')) {
                    $table->unsignedBigInteger('id_solicitud')->nullable()->change(); 
                } else {
                    $table->unsignedBigInteger('id_solicitud')->nullable()->index();
                }
                $table->foreign('id_solicitud')
                      ->references('id_solicitud')
                      ->on('solicitud')
                      ->nullOnDelete(); 
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('donacion') && Schema::hasColumn('donacion', 'id_solicitud')) {
            Schema::table('donacion', function (Blueprint $table) {
                try { $table->dropForeign(['id_solicitud']); } catch (\Throwable $e) { /* ignore */ }
            });
        }

        if (Schema::hasTable('solicitud')) {
            if (Schema::hasColumn('solicitud', 'id_solicitud') && !Schema::hasColumn('solicitud', 'id')) {
                Schema::table('solicitud', function (Blueprint $table) {
                    $table->renameColumn('id_solicitud', 'id');
                });
            }
        }
    }
};
