<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['paciente', 'especialidad'])->orderBy('fecha_hora', 'asc')->get();
        $pacientes = Paciente::all();
        $especialidades = Especialidad::all();
        return view('citas.index', compact('citas', 'pacientes', 'especialidades'));
    }

    public function agendar(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'medico_nombre' => 'required|string',
            'fecha_hora' => 'required|date|after:now',
        ]);

        // Algoritmo matemático/lógico de control de colisión de agendas
        $choqueAgenda = Cita::where('medico_nombre', $request->medico_nombre)
                            ->where('fecha_hora', $request->fecha_hora)
                            ->where('estado', 'Programada')
                            ->exists();

        if ($choqueAgenda) {
            return redirect()->back()->with('error', 'Alerta: El médico seleccionado ya se encuentra ocupado en ese horario.');
        }

        Cita::create($request->all());
        return redirect()->back()->with('exito', 'La cita médica ha sido agendada con éxito.');
    }

    public function cancelar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->estado = 'Cancelada'; // Borrado lógico para resguardar trazabilidad (Fiabilidad)
        $cita->save();
        return redirect()->back()->with('exito', 'Cita cancelada correctamente.');
    }

    public function historial($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);
        $historial = Cita::where('paciente_id', $paciente_id)
                         ->with('especialidad')
                         ->orderBy('fecha_hora', 'desc')
                         ->get();
        return view('citas.historial', compact('paciente', 'historial'));
    }
}