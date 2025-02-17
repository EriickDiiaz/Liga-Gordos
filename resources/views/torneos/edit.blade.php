@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Torneo: {{ $torneo->nombre }}</h1>

    <form action="{{ route('torneos.update', $torneo) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card mb-4">
            <div class="card-header">
                <h2>Detalles del Torneo</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Torneo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $torneo->nombre }}" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Torneo</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="eliminatoria" {{ $torneo->tipo == 'eliminatoria' ? 'selected' : '' }}>Eliminatoria</option>
                        <option value="liga" {{ $torneo->tipo == 'liga' ? 'selected' : '' }}>Liga</option>
                        <option value="mixto" {{ $torneo->tipo == 'mixto' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $torneo->fecha_inicio->format('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="planificado" {{ $torneo->estado == 'planificado' ? 'selected' : '' }}>Planificado</option>
                        <option value="en_curso" {{ $torneo->estado == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="finalizado" {{ $torneo->estado == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 mb-4">
            <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary m-1">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
            @can('editar torneos') 
            <button type="submit" class="btn btn-outline-primary m-1">Actualizar Torneo</button>
            @endcan
        </div>
    </form>

    <div class="row mt-4"> 
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Grupos</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('torneos.addGroup', $torneo) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre del grupo" required>
                            @can('editar torneos')
                            <button type="submit" class="btn btn-outline-primary">Agregar Grupo</button>
                            @endcan
                        </div>
                    </form>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @foreach($torneo->grupos as $grupo)
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        {{ $grupo->nombre }}
                                        @can('borrar torneos')
                                        <form action="{{ route('torneos.removeGroup', [$torneo, $grupo]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach($grupo->equipos as $equipo)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px;">
                                                        {{ $equipo->nombre }}
                                                    </div>
                                                    @can('borrar torneos')
                                                    <form action="{{ route('torneos.removeEquipo', [$torneo, $equipo]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Agregar Equipo a Grupo</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('torneos.addEquipo', $torneo) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="equipo_id" class="form-label">Equipo</label>
                            <select name="equipo_id" id="equipo_id" class="form-select" required>
                                @foreach(App\Models\Equipo::all() as $equipo)
                                    <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="grupo_id" class="form-label">Grupo</label>
                            <select name="grupo_id" id="grupo_id" class="form-select" required>
                                @foreach($torneo->grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @can('editar torneos')
                        <button type="submit" class="btn btn-outline-primary">Agregar Equipo al Grupo</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Add any necessary JavaScript here
    });
</script>
@endpush

