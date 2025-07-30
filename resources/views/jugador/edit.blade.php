@extends('layouts.app')
@section('title', 'Editar Jugador')
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
        <h1 class="mb-3 text-center"><i class="fa-solid fa-user me-2"></i>Editar Jugador: {{ $jugador->nombre }}</h1>
    </div>

    <!-- Contenido -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('jugador.update', $jugador) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Jugador</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                        value="{{ $jugador->nombre }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="cedula">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula"
                        value="{{ $jugador->cedula }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                        value="{{ $jugador->fecha_nacimiento->format('Y-m-d') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="dorsal">Dorsal</label>
                    <input type="number" class="form-control" id="dorsal" name="dorsal" min="0"
                        max="999" value="{{ $jugador->dorsal }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="tipo">Tipo de Jugador</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="habilidoso" {{ $jugador->tipo == 'habilidoso' ? 'selected' : '' }}>Habilidoso
                        </option>
                        <option value="brazalete" {{ $jugador->tipo == 'brazalete' ? 'selected' : '' }}>Brazalete
                        </option>
                        <option value="portero" {{ $jugador->tipo == 'portero' ? 'selected' : '' }}>Portero</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="equipo_id">Equipo</label>
                    <select class="form-control" id="equipo_id" name="equipo_id" required>
                        @foreach ($equipos->sortBy('nombre') as $equipo)
                            <option value="{{ $equipo->id }}"
                                {{ $jugador->equipo_id == $equipo->id ? 'selected' : '' }}>
                                {{ $equipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="foto">Foto del Jugador</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                    @if ($jugador->foto)
                        <img src="{{ asset('storage/' . $jugador->foto) }}" alt="{{ $jugador->nombre }}"
                            class="mt-2" style="max-width: 200px;">
                    @endif
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Jugadores')
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa-solid fa-arrows-rotate me-2"></i>Actualizar Jugador
                        </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
