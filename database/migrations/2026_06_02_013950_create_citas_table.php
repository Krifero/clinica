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
    Schema::create('citas', function (Blueprint $table) {
        $table->id();
        
        // Llaves foráneas optimizadas y vinculadas correctamente:
        $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
        $table->foreignId('especialidad_id')->constrained('especialidades')->onDelete('cascade');
        
        $table->string('medico_nombre');
        $table->datetime('fecha_hora');
        $table->string('estado')->default('Programada');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
