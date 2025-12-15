<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
            $table->text('imagen_evidencia')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('historial_seguimiento_donaciones', function (Blueprint $table) {
            $table->string('imagen_evidencia', 255)->nullable()->change();
        });
    }
};
