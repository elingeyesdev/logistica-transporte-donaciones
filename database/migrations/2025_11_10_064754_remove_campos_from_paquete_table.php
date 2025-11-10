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
        Schema::table('paquete', function (Blueprint $table) {
        if (Schema::hasColumn('paquete', 'descripcion')) $table->dropColumn('descripcion');
        if (Schema::hasColumn('paquete', 'categoria')) $table->dropColumn('categoria');
        if (Schema::hasColumn('paquete', 'cantidad_total')) $table->dropColumn('cantidad_total');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paquete', function (Blueprint $table) {
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable();
            $table->integer('cantidad_total')->nullable();
        });
    }
};
