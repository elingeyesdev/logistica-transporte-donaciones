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
        Schema::table('solicitud', function (Blueprint $table) {
            $table->string('nombre_referencia',255)
                  ->nullable()
                  ->after('fecha_necesidad');
              $table->integer('celular_referencia', false, true)
                  ->nullable()
                  ->after('nombre_referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            $table->dropColumn(['nombre_referencia', 'celular_referencia']);
        });
    }
};
