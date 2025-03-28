@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Crear Nuevo Partido</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('partidos.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Partido</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="grupo">Partido de Liga (Fase de Grupos)</option>
                        <option value="eliminatoria">Partido de Eliminatoria</option>
                        <option value="amistoso">Partido Amistoso</option>
                    </select>
                </div>
                
                <!-- Sección para partidos de Liga -->
                <div id="seccion-liga" class="d-none">
                    <div class="mb-3">
                        <label for="torneo_id_liga" class="form-label">Torneo</label>
                        <select name="torneo_id" id="torneo_id_liga" class="form-control">
                            <option value="">Seleccione un torneo</option>
                            @foreach($torneos->sortBy('nombre') as $torneo)
                                <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grupo_id" class="form-label">Grupo</label>
                        <select name="grupo_id" id="grupo_id" class="form-control" disabled>
                            <option value="">Seleccione un grupo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fase_liga" class="form-label">Fase</label>
                        <input type="text" name="fase" id="fase_liga" class="form-control" value="Fase de Grupos">
                    </div>
                </div>
                
                <!-- Sección para partidos de Eliminatoria -->
                <div id="seccion-eliminatoria" class="d-none">
                    <div class="mb-3">
                        <label for="torneo_id_eliminatoria" class="form-label">Torneo</label>
                        <select name="torneo_id" id="torneo_id_eliminatoria" class="form-control">
                            <option value="">Seleccione un torneo</option>
                            @foreach($torneos->sortBy('nombre') as $torneo)
                                <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fase_eliminatoria" class="form-label">Fase</label>
                        <select name="fase" id="fase_eliminatoria" class="form-control">
                            <option value="">Seleccione una fase</option>
                            <option value="Octavos de Final">Octavos de Final</option>
                            <option value="Cuartos de Final">Cuartos de Final</option>
                            <option value="Semifinal">Semifinal</option>
                            <option value="Final">Final</option>
                        </select>
                    </div>
                    
                    <!-- Campo oculto para establecer es_ida como true por defecto -->
                    <input type="hidden" name="es_ida" value="1">
                    
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Este será un partido único de eliminatoria.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crear_vuelta" id="crear_vuelta" value="1" checked>
                            <label class="form-check-label" for="crear_vuelta">
                                Crear automáticamente el partido de vuelta
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Sección para partidos Amistosos -->
                <div id="seccion-amistoso" class="d-none">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ej: Partido amistoso de pretemporada">
                    </div>
                </div>
                
                <!-- Información general del partido -->
                <div class="mb-3">
                    <label for="equipo_local_id" class="form-label">Equipo Local</label>
                    <select name="equipo_local_id" id="equipo_local_id" class="form-control" required>
                        <option value="">Seleccione el equipo local</option>
                        @foreach($equipos->sortBy('nombre') as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="equipo_visitante_id" class="form-label">Equipo Visitante</label>
                    <select name="equipo_visitante_id" id="equipo_visitante_id" class="form-control" required>
                        <option value="">Seleccione el equipo visitante</option>
                        @foreach($equipos->sortBy('nombre') as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha" id="fecha" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="programado">Programado</option>
                        <option value="en_curso">En Curso</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
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
        const tipoSelect = document.getElementById('tipo');
        const seccionLiga = document.getElementById('seccion-liga');
        const seccionEliminatoria = document.getElementById('seccion-eliminatoria');
        const seccionAmistoso = document.getElementById('seccion-amistoso');
        const torneoSelectLiga = document.getElementById('torneo_id_liga');
        const torneoSelectEliminatoria = document.getElementById('torneo_id_eliminatoria');
        const grupoSelect = document.getElementById('grupo_id');
        const equipoLocalSelect = document.getElementById('equipo_local_id');
        const equipoVisitanteSelect = document.getElementById('equipo_visitante_id');
        const faseLigaInput = document.getElementById('fase_liga');
        const faseEliminatoriaSelect = document.getElementById('fase_eliminatoria');

        // Mostrar/ocultar secciones según el tipo de partido
        tipoSelect.addEventListener('change', function() {
            seccionLiga.classList.add('d-none');
            seccionEliminatoria.classList.add('d-none');
            seccionAmistoso.classList.add('d-none');

            // Desactivar todos los campos de todas las secciones
            const allInputs = document.querySelectorAll('#seccion-liga input, #seccion-liga select, #seccion-eliminatoria input, #seccion-eliminatoria select, #seccion-amistoso input');
            allInputs.forEach(input => {
                input.disabled = true;
            });

            if (this.value === 'grupo') {
                seccionLiga.classList.remove('d-none');
                // Activar campos de la sección liga
                const ligaInputs = document.querySelectorAll('#seccion-liga input, #seccion-liga select');
                ligaInputs.forEach(input => {
                    if (input.id !== 'grupo_id' || !input.disabled) {
                        input.disabled = false;
                    }
                });
            } else if (this.value === 'eliminatoria') {
                seccionEliminatoria.classList.remove('d-none');
                // Activar campos de la sección eliminatoria
                const eliminatoriaInputs = document.querySelectorAll('#seccion-eliminatoria input, #seccion-eliminatoria select');
                eliminatoriaInputs.forEach(input => {
                    input.disabled = false;
                });
            } else if (this.value === 'amistoso') {
                seccionAmistoso.classList.remove('d-none');
                // Activar campos de la sección amistoso
                const amistosoInputs = document.querySelectorAll('#seccion-amistoso input');
                amistosoInputs.forEach(input => {
                    input.disabled = false;
                });
            }
        });

        // Cargar grupos cuando se selecciona un torneo en la sección de Liga
        torneoSelectLiga.addEventListener('change', function() {
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

        // Cargar equipos cuando se selecciona un grupo
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
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Cargar equipos del torneo cuando se selecciona un torneo en la sección de Eliminatoria
        torneoSelectEliminatoria.addEventListener('change', function() {
            if (this.value) {
                fetch(`{{ route('partidos.getEquiposTorneo') }}?torneo_id=${this.value}`)
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
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endpush

@endsection

