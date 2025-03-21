@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Editar Partido</h1> 
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('partidos.update', $partido) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="torneo_id" class="form-label">Torneo</label>
                    <select name="torneo_id" id="torneo_id" class="form-control" required>
                        @foreach($torneos->sortBy('nombre') as $torneo)
                            <option value="{{ $torneo->id }}" {{ $partido->torneo_id == $torneo->id ? 'selected' : '' }}>
                                {{ $torneo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="grupo_id" class="form-label">Grupo</label>
                    <select name="grupo_id" id="grupo_id" class="form-control" required>
                        <option value="">Sin grupo</option>
                        @foreach($grupos->sortBy('nombre') as $grupo)
                            <option value="{{ $grupo->id }}" {{ $partido->grupo_id == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="equipo_local_id" class="form-label">Equipo Local</label>
                    <select name="equipo_local_id" id="equipo_local_id" class="form-control" required>
                        @foreach($equipos->sortBy('nombre') as $equipo)
                            <option value="{{ $equipo->id }}" {{ $partido->equipo_local_id == $equipo->id ? 'selected' : '' }}>
                                {{ $equipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="equipo_visitante_id" class="form-label">Equipo Visitante</label>
                    <select name="equipo_visitante_id" id="equipo_visitante_id" class="form-control" required>
                        @foreach($equipos->sortBy('nombre') as $equipo)
                            <option value="{{ $equipo->id }}" {{ $partido->equipo_visitante_id == $equipo->id ? 'selected' : '' }}>
                                {{ $equipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control" value="{{ $partido->fecha->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="grupo" {{ $partido->tipo == 'grupo' ? 'selected' : '' }}>Grupo</option>
                        <option value="eliminatoria" {{ $partido->tipo == 'eliminatoria' ? 'selected' : '' }}>Eliminatoria</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fase" class="form-label">Fase</label>
                    <input type="text" name="fase" id="fase" class="form-control" value="{{ $partido->fase }}">
                </div>
                <div class="mb-3">
                    <label for="goles_local" class="form-label">Goles Local</label>
                    <input type="number" name="goles_local" id="goles_local" class="form-control" value="{{ $partido->goles_local }}" min="0">
                </div>
                <div class="mb-3">
                    <label for="goles_visitante" class="form-label">Goles Visitante</label>
                    <input type="number" name="goles_visitante" id="goles_visitante" class="form-control" value="{{ $partido->goles_visitante }}" min="0">
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="programado" {{ $partido->estado == 'programado' ? 'selected' : '' }}>Programado</option>
                        <option value="en_curso" {{ $partido->estado == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="finalizado" {{ $partido->estado == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('partidos.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Partidos')
                    <button type="submit" class="btn btn-outline-primary m-1">Actualizar Partido</button>
                    @endcan                 
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

