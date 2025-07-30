@extends('layouts.app')
@section('title', 'Crear Jugador')
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
        <h1 class="mb-3 text-center"><i class="fa-solid fa-user me-2"></i>Crear Nuevo Jugador</h1>
    </div>

    <!-- Contenido -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('jugador.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre del Jugador</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                            value="{{ old('nombre') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="cedula">Cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula" required
                            value="{{ old('cedula') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required
                            value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="dorsal">Dorsal</label>
                        <input type="number" class="form-control" id="dorsal" name="dorsal" min="0"
                            max="999" required value="{{ old('dorsal') }}" pattern="\d{1,3}"
                            title="Debe ser un número entre 0 y 999">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipo">Tipo de Jugador</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="" disabled selected>Seleccione un tipo</option>
                            <option value="habilidoso">Habilidoso</option>
                            <option value="brazalete">Brazalete</option>
                            <option value="portero">Portero</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="equipo_id">Equipo</label>
                        <select class="form-control" id="equipo_id" name="equipo_id" required>
                            @foreach ($equipos->sortBy('nombre') as $equipo)
                                <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="foto">Foto del Jugador</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a la lista
                        </a>
                        @can('Crear Jugadores')
                            <button type="submit" class="btn btn-outline-success">
                                <i class="fas fa-plus me-2"></i>Crear Jugador
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
