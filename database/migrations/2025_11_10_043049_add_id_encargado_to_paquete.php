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
        Schema::table('paquete', function (Blueprint $t) {
            if (!Schema::hasColumn('paquete', 'id_encargado')) {
                $t->string('id_encargado')->nullable()->after('descripcion');
                $t->foreign('id_encargado')->references('ci')->on('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paquete', function (Blueprint $t) {
            if (Schema::hasColumn('paquete', 'id_encargado')) {
                $t->dropForeign(index: ['id_encargado']);
                $t->dropColumn('id_encargado');
            }
        });
    }
};
