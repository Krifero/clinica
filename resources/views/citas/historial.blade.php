<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Clínico - {{ $paciente->nombre_completo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="/agenda"><i class="bi bi-arrow-left-circle me-2"></i> Volver a la Agenda</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow border-0 mb-4">
            <div class="card-body p-4 bg-white">
                <h3 class="fw-bold text-primary mb-1"><i class="bi bi-person-badge-fill me-2"></i> {{ $paciente->nombre_completo }}</h3>
                <p class="text-muted mb-0">Documento: {{ $paciente->documento }} | Teléfono: {{ $paciente->telefono }} | Correo: {{ $paciente->correo }}</p>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i> Historial de Atenciones y Citas (RF04)</h5>
            </div>
            <div class="card-body p-0">
                @if($historial->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-folder-x display-4"></i>
                        <p class="mt-2 mb-0">Este paciente no registra antecedentes ni citas médicas en el sistema.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Fecha y Hora</th>
                                    <th>Especialidad</th>
                                    <th>Médico Tratante</th>
                                    <th>Estado de la Cita</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historial as $item)
                                <tr class="{{ $item->estado == 'Cancelada' ? 'table-danger opacity-50' : '' }}">
                                    <td class="ps-4 fw-bold">{{ date('d/m/Y h:i A', strtotime($item->fecha_hora)) }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->especialidad->nombre_especialidad }}</span></td>
                                    <td>{{ $item->medico_nombre }}</td>
                                    <td>
                                        <span class="badge {{ $item->estado == 'Programada' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->estado }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>
</html>