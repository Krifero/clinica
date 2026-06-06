<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // Listar todos los pacientes (Read)
    public function index()
    {
        $pacientes = Paciente::orderBy('id', 'desc')->get();
        return view('pacientes.index', compact('pacientes'));
    }

    // Almacenar nuevo paciente (Create)
    public function store(Request $request)
    {
        // 1. Validamos cada campo según tus nuevas reglas
        $request->validate([
            'cedula' => 'required|unique:pacientes,cedula',
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100', // NULLABLE = Opcional
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100', // NULLABLE = Opcional
            'telefono' => 'required',
            'correo' => 'required|email',
            'fecha_nacimiento' => 'required|date'
        ]);

        // 2. Al usar $request->all(), Laravel tomará automáticamente todos los campos del formulario
        // y los inyectará en los campos correspondientes que pusimos en el $fillable del Modelo.
        Paciente::create($request->all());

        return redirect()->back()->with('exito', 'Paciente registrado exitosamente con sus datos estructurados.');
    }

    // Eliminar de forma lógica/física (Delete)
    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()->back()->with('exito', 'Paciente removido del sistema correctamente.');
    }
}