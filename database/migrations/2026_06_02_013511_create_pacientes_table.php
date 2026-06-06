<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('pacientes', function (Blueprint $table) {
        $table->id();
        $table->string('cedula')->unique(); // <- Asegúrate de que diga 'cedula' y no 'documento'
        $table->string('primer_nombre');
        $table->string('segundo_nombre')->nullable();
        $table->string('primer_apellido');
        $table->string('segundo_apellido')->nullable();
        $table->string('telefono');
        $table->string('correo');
        $table->date('fecha_nacimiento');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
