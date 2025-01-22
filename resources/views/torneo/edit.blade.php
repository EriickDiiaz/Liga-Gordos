@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Torneo: {{ $torneo->nombre }}</h1>
    
    <form action="{{ route('torneos.update', $torneo) }}" method="POST">
        @csrf
        @method('PUT')
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
        <button type="submit" class="btn btn-primary">Actualizar Torneo</button>
    </form>
</div>
@endsection