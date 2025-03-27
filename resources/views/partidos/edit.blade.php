@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Editar Partido</h1> 
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('partidos.update', $partido) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Campos específicos según el tipo de partido -->
                @if($partido->esLiga())
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2>Detalles del Partido de Liga</h2>
                        </div>
                        <div class="card-body">
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
                                    @foreach($torneos->find($partido->torneo_id)->grupos as $grupo)
                                        <option value="{{ $grupo->id }}" {{ $partido->grupo_id == $grupo->id ? 'selected' : '' }}>
                                            {{ $grupo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @elseif($partido->esEliminatoria())
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2>Detalles del Partido de Eliminatoria</h2>
                        </div>
                        <div class="card-body">
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
                                <label for="fase" class="form-label">Fase</label>
                                <select name="fase" id="fase" class="form-control" required>
                                    <option value="Octavos de Final" {{ $partido->fase == 'Octavos de Final' ? 'selected' : '' }}>Octavos de Final</option>
                                    <option value="Cuartos de Final" {{ $partido->fase == 'Cuartos de Final' ? 'selected' : '' }}>Cuartos de Final</option>
                                    <option value="Semifinal" {{ $partido->fase == 'Semifinal' ? 'selected' : '' }}>Semifinal</option>
                                    <option value="Final" {{ $partido->fase == 'Final' ? 'selected' : '' }}>Final</option>
                                    <option value="{{ $partido->fase }}" {{ !in_array($partido->fase, ['Octavos de Final', 'Cuartos de Final', 'Semifinal', 'Final']) ? 'selected' : '' }}>{{ $partido->fase }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <p><strong>Tipo:</strong> {{ $partido->esIda() ? 'Partido de IDA' : 'Partido de VUELTA' }}</p>
                                @if(!$partido->esIda() && $partido->partidoRelacionado)
                                    <p><strong>Partido de ida relacionado:</strong> {{ $partido->partidoRelacionado->equipoLocal->nombre }} vs {{ $partido->partidoRelacionado->equipoVisitante->nombre }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($partido->esAmistoso())
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2>Detalles del Partido Amistoso</h2>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ $partido->descripcion }}" required>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Información general del partido -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>Información General</h2>
                    </div>
                    <div class="card-body">
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
                    </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Si es necesario, agregar JavaScript para manejar cambios en el torneo y cargar grupos
        const torneoSelect = document.getElementById('torneo_id');
        const grupoSelect = document.getElementById('grupo_id');
        
        if (torneoSelect && grupoSelect) {
            torneoSelect.addEventListener('change', function() {
                if (this.value) {
                    fetch(`{{ route('partidos.getGrupos') }}?torneo_id=${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            grupoSelect.innerHTML = '<option value="">Sin grupo</option>';
                            data.forEach(grupo => {
                                grupoSelect.innerHTML += `<option value="${grupo.id}">${grupo.nombre}</option>`;
                            });
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    grupoSelect.innerHTML = '<option value="">Sin grupo</option>';
                }
            });
        }
    });
</script>
@endpush

