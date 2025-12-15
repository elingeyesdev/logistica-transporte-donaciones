<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            $table->string('ci_voluntario')
                  ->nullable()
                  ->after('id_destino')
                  ->comment('CI del voluntario que aprueba o niega la solicitud');
        });
    }

    public function down(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            $table->dropColumn('ci_voluntario');
        });
    }
};
