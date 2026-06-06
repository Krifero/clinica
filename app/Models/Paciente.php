<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'cedula', 
        'primer_nombre', 
        'segundo_nombre', 
        'primer_apellido', 
        'segundo_apellido', 
        'telefono', 
        'correo', 
        'fecha_nacimiento'
    ];

    /**
     * Atributo dinámico (Accesor) para obtener el nombre completo fácilmente
     * sin tener que concatenar en cada vista.
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->primer_nombre} {$this->segundo_nombre} {$this->primer_apellido} {$this->segundo_apellido}");
    }
}