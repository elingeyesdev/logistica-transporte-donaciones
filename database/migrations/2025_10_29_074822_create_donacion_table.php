<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donacion', function (Blueprint $table) {
            $table->id('id_donacion');
            $table->unsignedBigInteger('id_solicitud')->nullable();
            $table->text('descripcion');
            $table->integer('cantidad_total');
            $table->string('estado_entrega')->default('En preparación');
            $table->string('ubicacion_actual')->nullable();
            $table->date('fecha_creacion')->default(now());
            $table->date('fecha_entrega')->nullable();
            $table->timestamps();

            // Relación con solicitud (si ya tienes la tabla solicitud)
            $table->foreign('id_solicitud')
                  ->references('id')
                  ->on('solicitud')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donacion');
    }
};
