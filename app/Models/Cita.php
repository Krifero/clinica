<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    // Campos que Laravel permite llenar mediante formularios (Carga masiva)
    protected $fillable = [
        'paciente_id', 
        'especialidad_id', 
        'medico_nombre', 
        'fecha_hora', 
        'estado'
    ];

    /**
     * Relación: Una cita pertenece a un Paciente (Muchos a Uno)
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Relación: Una cita pertenece a una Especialidad (Muchos a Uno)
     */
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }
}