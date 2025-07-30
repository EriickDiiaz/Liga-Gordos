@extends('layouts.app')
@section('title', 'Editar Torneo')
@section('content')

<!-- Mensajes y alertas -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    
    <!-- Titulo -->
    <div>
        <h1 class="mb-3 text-center"><i class="fa-solid fa-trophy me-2"></i>Editar Torneo: {{ $torneo->nombre }}</h1>
    </div>
    
    <!-- Contenido -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('torneos.update', $torneo) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Torneo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $torneo->nombre }}" required>
                </div>
        
                <div class="form-group mb-3">
                    <label for="tipo">Tipo de Torneo</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="eliminatoria" {{ $torneo->tipo == 'eliminatoria' ? 'selected' : '' }}>Eliminatoria</option>
                        <option value="liga" {{ $torneo->tipo == 'liga' ? 'selected' : '' }}>Liga</option>
                        <option value="mixto" {{ $torneo->tipo == 'mixto' ? 'selected' : '' }}>Mixto</option>
                    </select>
                </div>                
                <div class="form-group mb-3">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $torneo->fecha_inicio->format('Y-m-d') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="planificado" {{ $torneo->estado == 'planificado' ? 'selected' : '' }}>Planificado</option>
                        <option value="en_curso" {{ $torneo->estado == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="finalizado" {{ $torneo->estado == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Torneos') 
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrows-rotate me-2"></i>Actualizar Torneo
                    </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>

    <!-- Agregar Grupos -->
    <div class="row justify-content-center mt-4">
        <!-- Grupos -->
        <div class="col-md-8">
            <div class="p-3 border rounded mb-4">
                <h2 class="mb-3 text-center"><i class="fa-solid fa-users me-2"></i>Grupos</h2>
                <form action="{{ route('torneos.addGroup', $torneo) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="nombre" class="form-control" placeholder="Grupo X" required>
                        @can('Editar Torneos')
                        <button type="submit" class="btn btn-outline-primary">Agregar Grupo</button>
                        @endcan
                    </div>
                </form>
                <div class="row">
                    @foreach($torneo->grupos as $grupo)
                        <div class="col-12 mb-3">
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">{{ $grupo->nombre }}</span>
                                    @can('Borrar Torneos')
                                    <form action="{{ route('torneos.removeGroup', [$torneo, $grupo]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar Grupo">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                                <ul class="list-group list-group-flush">
                                    @foreach($grupo->equipos->sortBy('nombre') as $equipo)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px;">
                                                {{ $equipo->nombre }}
                                            </div>
                                            @can('Borrar Torneos')
                                            <form action="{{ route('torneos.removeEquipo', [$torneo, $equipo]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar Equipo">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    
        <!-- Agregar equipo a grupo debajo de Grupos -->
        <div class="col-md-8">
            <div class="p-3 border rounded mb-4">
                <h2 class="mb-3 text-center"><i class="fa-solid fa-user-plus me-2"></i>Agregar Equipo a Grupo</h2>
                <form action="{{ route('torneos.addEquipo', $torneo) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label for="equipo_id" class="form-label">Equipo</label>
                        <select name="equipo_id" id="equipo_id" class="form-select" required>
                            @foreach($equipos->sortBy('nombre') as $equipo)
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
                    @can('Editar Torneos')
                    <button type="submit" class="btn btn-outline-primary">Agregar Equipo al Grupo</button>
                    @endcan
                </form>
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

