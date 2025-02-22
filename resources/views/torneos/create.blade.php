@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Nuevo Torneo</h1>

    <form action="{{ route('torneos.store') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="card-header">
                <h2>Detalles del Torneo</h2>
            </div>
            <div class="card-body">
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
            </div>
        </div>

        <div class="text-center mt-3 mb-4">
            <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary m-1">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
            @can('Crear Torneos')
            <button type="submit" class="btn btn-outline-success m-1">Crear Torneo</button>
            @endcan
        </div>
    </form>
</div>
@endsection

