<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            if (!Schema::hasColumn('historial_seguimiento_donaciones','user_id')) {
                $t->unsignedBigInteger('user_id')->nullable()->after('ci_usuario');
                $t->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            }
        });

    }

    public function down(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $t) {
            try { $t->dropForeign(['user_id']); } catch (\Throwable $e) {}
            $t->dropColumn('user_id');
        });
    }
};
