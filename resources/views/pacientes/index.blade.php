<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedManager - Gestión de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="/agenda"><i class="bi bi-heart-pulse-fill text-danger me-2"></i> MedManager</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white fw-bold" href="/agenda"><i class="bi bi-calendar-event me-1"></i> Ir a la Agenda</a>
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

        <div class="row">
            <div class="col-md-5">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Registrar Paciente</h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="{{ route('pacientes.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Cédula de Ciudadanía <span class="text-danger">*</span></label>
                                <input type="text" name="cedula" class="form-control" required placeholder="Ej: 10203040">
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label fw-bold text-secondary">Primer Nombre <span class="text-danger">*</span></label>
                                    <input type="text" name="primer_nombre" class="form-control" required placeholder="Ej: Juan">
                                </div>
                                <div class="col">
                                    <label class="form-label fw-bold text-secondary">Segundo Nombre</label>
                                    <input type="text" name="segundo_nombre" class="form-control" placeholder="Opcional">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label fw-bold text-secondary">Primer Apellido <span class="text-danger">*</span></label>
                                    <input type="text" name="primer_apellido" class="form-control" required placeholder="Ej: Pérez">
                                </div>
                                <div class="col">
                                    <label class="form-label fw-bold text-secondary">Segundo Apellido</label>
                                    <input type="text" name="segundo_apellido" class="form-control" placeholder="Opcional">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Teléfono <span class="text-danger">*</span></label>
                                <input type="text" name="telefono" class="form-control" required placeholder="Ej: 3157008090">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="correo" class="form-control" required placeholder="Ej: juan.perez@correo.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-2">Guardar Paciente</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i> Base de Datos de Pacientes</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Cédula / Nombre Completo</th>
                                        <th>Contacto</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pacientes as $paciente)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="text-muted small d-block">CC {{ $paciente->cedula }}</span>
                                            <span class="fw-bold text-primary">{{ $paciente->nombre_completo }}</span>
                                        </td>
                                        <td>
                                            <small class="d-block"><i class="bi bi-telephone me-1 text-secondary"></i> {{ $paciente->telefono }}</small>
                                            <small class="d-block text-muted"><i class="bi bi-envelope me-1"></i> {{ $paciente->correo }}</small>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="bi bi-person-x display-6"></i>
                                            <p class="mt-2 mb-0">No hay pacientes registrados.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>