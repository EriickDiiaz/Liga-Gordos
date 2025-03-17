@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Editar Jugador: {{ $jugador->nombre }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('jugador.update', $jugador) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Jugador</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $jugador->nombre }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="cedula">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" value="{{ $jugador->cedula }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $jugador->fecha_nacimiento->format('Y-m-d') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="dorsal">Dorsal</label>
                    <input type="number" class="form-control" id="dorsal" name="dorsal" min="1" max="99" value="{{ $jugador->dorsal }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="tipo">Tipo de Jugador</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="habilidoso" {{ $jugador->tipo == 'habilidoso' ? 'selected' : '' }}>Habilidoso</option>
                        <option value="brazalete" {{ $jugador->tipo == 'brazalete' ? 'selected' : '' }}>Brazalete</option>
                        <option value="portero" {{ $jugador->tipo == 'portero' ? 'selected' : '' }}>Portero</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="equipo_id">Equipo</label>
                    <select class="form-control" id="equipo_id" name="equipo_id" required>
                        @foreach($equipos->sortBy('nombre') as $equipo)
                            <option value="{{ $equipo->id }}" {{ $jugador->equipo_id == $equipo->id ? 'selected' : '' }}>
                                {{ $equipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="foto">Foto del Jugador</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                    @if($jugador->foto)
                        <img src="{{ asset('storage/' . $jugador->foto) }}" alt="{{ $jugador->nombre }}" class="mt-2" style="max-width: 200px;">
                    @endif
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('jugador.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Jugadores')
                    <button type="submit" class="btn btn-outline-primary m-1">Actualizar Jugador</button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection

