<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Especialidad;

class EspecialidadSeeder extends Seeder
{
    public function run(): void
    {
        Especialidad::create(['nombre_especialidad' => 'Medicina General']);
        Especialidad::create(['nombre_especialidad' => 'Pediatría']);
        Especialidad::create(['nombre_especialidad' => 'Cardiología']);
        Especialidad::create(['nombre_especialidad' => 'Odontología']);
        Especialidad::create(['nombre_especialidad' => 'Ginecología']);
    }
}