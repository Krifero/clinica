<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;

// Ruta de bienvenida redireccionada
Route::get('/', function () {
    return redirect('/agenda');
});

// Rutas Corporativas del Sistema
Route::resource('pacientes', PacienteController::class);
Route::get('/agenda', [CitaController::class, 'index']);
Route::post('/citas/agendar', [CitaController::class, 'agendar']);
Route::post('/citas/cancelar/{id}', [CitaController::class, 'cancelar']);
Route::get('/historial/{paciente_id}', [CitaController::class, 'historial']);