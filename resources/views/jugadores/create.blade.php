@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Crear Nuevo Jugador</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('jugadores.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Jugador</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group mb-3">
                    <label for="cedula">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" required>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-group mb-3">
                    <label for="dorsal">Dorsal</label>
                    <input type="number" class="form-control" id="dorsal" name="dorsal" min="1" max="99" required>
                </div>
                <div class="form-group mb-3">
                    <label for="tipo">Tipo de Jugador</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="habilidoso">Habilidoso</option>
                        <option value="brazalete">Brazalete</option>
                        <option value="portero">Portero</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="equipo_id">Equipo</label>
                    <select class="form-control" id="equipo_id" name="equipo_id" required>
                        @foreach($equipos as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="foto">Foto del Jugador</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('jugadores.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>    
                    <button type="submit" class="btn btn-outline-success m-1">Crear Jugador</button>
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection