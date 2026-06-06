<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    // ESTA LÍNEA ARREGLA EL ERROR DEL SEEDER:
    protected $table = 'especialidades'; 

    protected $fillable = ['nombre_especialidad'];
}