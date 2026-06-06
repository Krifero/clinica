<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica - Gestión de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="/agenda"><i class="bi bi-heart-pulse-fill text-danger me-2"></i> MedManager</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white fw-bold me-3" href="/pacientes"><i class="bi bi-people-fill me-1"></i> Gestionar Pacientes</a>
                <a class="nav-link text-white active fw-bold" href="/agenda"><i class="bi bi-calendar-event me-1"></i> Agenda</a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        @if(session('exito'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('exito') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow border-0 mb-5">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i> Agendar Nueva Cita Médica</h5>
            </div>
            <div class="card-body p-4 bg-white">
                <form action="/citas/agendar" method="POST">
                    @csrf
                    <div class="row g-3">
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary">Paciente</label>
                            <select name="paciente_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}">CC {{ $paciente->cedula }} - {{ $paciente->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary">Especialidad</label>
                            <select name="especialidad_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($especialidades as $esp)
                                    <option value="{{ $esp->id }}">{{ $esp->nombre_especialidad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary">Nombre del Médico</label>
                            <input type="text" name="medico_nombre" class="form-control" placeholder="Ej: Dr. Alejandro Hoyos" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary">Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_hora" class="form-control" required>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">Confirmar Transacción (POST)</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-task me-2"></i> Monitoreo de Agenda Global</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Paciente</th>
                                <th>Especialidad</th>
                                <th>Médico</th>
                                <th>Fecha y Hora</th>
                                <th>Estado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                            <tr class="{{ $cita->estado == 'Cancelada' ? 'table-secondary text-muted' : '' }}">
                                <td class="ps-4 fw-bold">
                                    {{ $cita->paciente->nombre_completo }}
                                    <a href="/historial/{{ $cita->paciente_id }}" class="d-block small text-primary text-decoration-none mt-1"><i class="bi bi-folder2-open"></i> Ver Historial</a>
                                </td>
                                <td><span class="badge bg-secondary">{{ $cita->especialidad->nombre_especialidad }}</span></td>
                                <td>{{ $cita->medico_nombre }}</td>
                                <td>{{ date('d/m/Y h:i A', strtotime($cita->fecha_hora)) }}</td>
                                <td>
                                    <span class="badge {{ $cita->estado == 'Programada' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $cita->estado }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($cita->estado == 'Programada')
                                    <form action="/citas/cancelar/{{ $cita->id }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">Cancelar</button>
                                    </form>
                                    @else
                                        <span class="small text-muted">Liberado</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>