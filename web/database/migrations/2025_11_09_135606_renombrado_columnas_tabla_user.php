<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','email') && !Schema::hasColumn('users','correo_electronico')) {
                $table->renameColumn('email', 'correo_electronico');
            }

            if (!Schema::hasColumn('users','nombre')) {
                $table->string('nombre')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users','apellido')) {
                $table->string('apellido')->nullable()->after('nombre');
            }

            if (!Schema::hasColumn('users','ci'))        $table->string('ci')->nullable()->unique();
            if (!Schema::hasColumn('users','telefono'))  $table->string('telefono')->nullable();
            if (!Schema::hasColumn('users','administrador'))     $table->boolean('administrador')->default(false);
            if (!Schema::hasColumn('users','activo'))    $table->boolean('activo')->default(true);
        });
    }

       public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','correo_electronico') && !Schema::hasColumn('users','email')) {
                $table->renameColumn('correo_electronico','email');
            }
            if (Schema::hasColumn('users','nombre'))   $table->dropColumn('nombre');
            if (Schema::hasColumn('users','apellido')) $table->dropColumn('apellido');

            if (Schema::hasColumn('users','ci'))       $table->dropColumn('ci');
            if (Schema::hasColumn('users','telefono')) $table->dropColumn('telefono');
            if (Schema::hasColumn('users','administrador'))    $table->dropColumn('administrador');
            if (Schema::hasColumn('users','activo'))   $table->dropColumn('activo');
        });
    }

};
