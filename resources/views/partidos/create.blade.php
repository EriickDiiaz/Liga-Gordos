@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Crear Nuevo Partido</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('partidos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="torneo_id" class="form-label">Torneo</label>
                    <select name="torneo_id" id="torneo_id" class="form-control" required>
                        <option value="">Seleccione un torneo</option>
                        @foreach($torneos->sortBy('nombre') as $torneo)
                            <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="grupo_id" class="form-label">Grupo</label>
                    <select name="grupo_id" id="grupo_id" class="form-control" required disabled>
                        <option value="">Seleccione un grupo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="equipo_local_id" class="form-label">Equipo Local</label>
                    <select name="equipo_local_id" id="equipo_local_id" class="form-control" required disabled>
                        <option value="">Seleccione el equipo local</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="equipo_visitante_id" class="form-label">Equipo Visitante</label>
                    <select name="equipo_visitante_id" id="equipo_visitante_id" class="form-control" required disabled>
                        <option value="">Seleccione el equipo visitante</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="grupo">Grupo</option>
                        <option value="eliminatoria">Eliminatoria</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fase" class="form-label">Fase</label>
                    <input type="text" name="fase" id="fase" class="form-control">
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('partidos.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Crear Partidos')
                    <button type="submit" class="btn btn-outline-primary m-1">Crear Partido</button>
                    @endcan
                </div>
                
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const torneoSelect = document.getElementById('torneo_id');
        const grupoSelect = document.getElementById('grupo_id');
        const equipoLocalSelect = document.getElementById('equipo_local_id');
        const equipoVisitanteSelect = document.getElementById('equipo_visitante_id');

        torneoSelect.addEventListener('change', function() {
            if (this.value) {
                fetch(`{{ route('partidos.getGrupos') }}?torneo_id=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        // Ordenar grupos alfabéticamente
                        data.sort((a, b) => a.nombre.localeCompare(b.nombre));
                        
                        grupoSelect.innerHTML = '<option value="">Seleccione un grupo</option>';
                        data.forEach(grupo => {
                            grupoSelect.innerHTML += `<option value="${grupo.id}">${grupo.nombre}</option>`;
                        });
                        grupoSelect.disabled = false;
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                grupoSelect.innerHTML = '<option value="">Seleccione un grupo</option>';
                grupoSelect.disabled = true;
            }
        });

        grupoSelect.addEventListener('change', function() {
            if (this.value) {
                fetch(`{{ route('partidos.getEquipos') }}?grupo_id=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        // Ordenar equipos alfabéticamente por nombre
                        data.sort((a, b) => a.nombre.localeCompare(b.nombre));
                        
                        equipoLocalSelect.innerHTML = '<option value="">Seleccione el equipo local</option>';
                        equipoVisitanteSelect.innerHTML = '<option value="">Seleccione el equipo visitante</option>';
                        data.forEach(equipo => {
                            equipoLocalSelect.innerHTML += `<option value="${equipo.id}">${equipo.nombre}</option>`;
                            equipoVisitanteSelect.innerHTML += `<option value="${equipo.id}">${equipo.nombre}</option>`;
                        });
                        equipoLocalSelect.disabled = false;
                        equipoVisitanteSelect.disabled = false;
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                equipoLocalSelect.innerHTML = '<option value="">Seleccione el equipo local</option>';
                equipoVisitanteSelect.innerHTML = '<option value="">Seleccione el equipo visitante</option>';
                equipoLocalSelect.disabled = true;
                equipoVisitanteSelect.disabled = true;
            }
        });
    });
</script>
@endpush

@endsection

