@extends('layouts.app') 
@section('title', 'Crear Torneo')
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
        <h1 class="mb-3 text-center"><i class="fa-solid fa-shield me-2"></i>Crear Nuevo Torneo</h1>
    </div>

    <!-- Contenido -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('torneos.store') }}" method="POST">
                @csrf                
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Torneo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required value="{{ old('nombre') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="tipo" class="form-label">Tipo de Torneo</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="eliminatoria">Eliminatoria</option>
                        <option value="liga">Liga</option>
                        <option value="mixto">Mixto</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <div class="form-group mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="planificado">Planificado</option>
                        <option value="en_curso">En Curso</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a la lista
                    </a>
                    @can('Crear Torneos')
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-plus me-2"></i>Crear Torneo
                    </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
    
</div>
@endsection

