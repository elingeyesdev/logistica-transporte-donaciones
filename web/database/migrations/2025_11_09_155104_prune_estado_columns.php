<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estado', function (Blueprint $table) {
            if (Schema::hasColumn('estado', 'descripcion')) $table->dropColumn('descripcion');
            if (Schema::hasColumn('estado', 'color'))       $table->dropColumn('color');
            if (Schema::hasColumn('estado', 'tipo'))        $table->dropColumn('tipo'); 
        });
    }

    public function down(): void
    {
        Schema::table('estado', function (Blueprint $table) {
            if (!Schema::hasColumn('estado', 'descripcion')) $table->string('descripcion')->nullable();
            if (!Schema::hasColumn('estado', 'color'))       $table->string('color')->nullable();
            if (!Schema::hasColumn('estado', 'tipo'))        $table->string('tipo')->nullable();
        });
    }
};
