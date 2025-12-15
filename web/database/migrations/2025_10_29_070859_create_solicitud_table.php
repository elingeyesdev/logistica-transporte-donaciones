<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitud', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_solicitante');
            $table->date('fecha_creacion');
            $table->text('descripcion');
            $table->string('ubicacion');
            $table->string('estado')->default('pendiente');
            $table->string('codigo_seguimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud');
    }
};
