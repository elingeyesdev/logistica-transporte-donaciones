
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->boolean('active')->nullable();
            $table->boolean('admin')->nullable();
            $table->string('apellido', 255)->nullable();
            $table->string('ci', 255)->nullable()->unique();
            $table->string('contrasena', 255)->nullable();
            $table->string('correo_electronico', 255)->nullable()->unique();
            $table->string('nombre', 255)->nullable();
            $table->string('telefono', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('usuario');
    }
};



