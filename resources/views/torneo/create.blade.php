@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Nuevo Torneo</h1>
    
    <form action="{{ route('torneo.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Torneo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Torneo</label>
            <select class="form-select" id="tipo" name="tipo" required>
                <option value="eliminatoria">Eliminatoria</option>
                <option value="liga">Liga</option>
                <option value="mixto">Mixto</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="planificado">Planificado</option>
                <option value="en_curso">En Curso</option>
                <option value="finalizado">Finalizado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Torneo</button>
    </form>
</div>
@endsection