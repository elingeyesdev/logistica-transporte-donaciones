<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estado', function (Blueprint $table) {
            $table->id('id_estado');
            $table->string('nombre_estado');
            $table->text('descripcion')->nullable();
            $table->string('tipo'); // Ej: "solicitud" o "donacion"
            $table->string('color')->nullable(); // Ej: "#28a745" (verde)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado');
    }
};
