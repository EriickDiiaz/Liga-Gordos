@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Torneos</h1>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @can('Crear Torneos')
    <a href="{{ route('torneos.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Torneo</a>
    @endcan
    
    <div class="accordion" id="torneosAccordion">
        @foreach($torneos as $index => $torneo)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $torneo->id }}">
                    <button class="accordion-button bg-warning text-dark {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $torneo->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $torneo->id }}">
                        <i class="fa-solid fa-trophy me-2"></i>
                        <strong>{{ $torneo->nombre }}</strong>
                        <div class="ms-auto me-3">
                            <span class="badge bg-primary me-2">{{ ucfirst($torneo->estado) }}</span>
                            <span class="badge bg-secondary">{{ $torneo->equipos->count() }} equipos</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $torneo->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $torneo->id }}" data-bs-parent="#torneosAccordion">
                    <div class="accordion-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Detalles del Torneo</h5>
                                <p><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
                                <p><strong>Fecha de Inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
                                <p><strong>Estado:</strong> {{ ucfirst($torneo->estado) }}</p>
                                <p><strong>Equipos participantes:</strong> {{ $torneo->equipos->count() }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Acciones</h5>
                                <a href="{{ route('plantillas.index', $torneo->id) }}" class="btn btn-outline-success m-1">
                                    <i class="fas fa-user-plus"></i> Inscribir Jugadores
                                </a>
                                @can('Editar Torneos')
                                <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-outline-primary m-1">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @endcan
                                @can('Borrar Torneos')
                                <form action="{{ route('torneos.destroy', $torneo) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-torneo m-1" data-id="{{ $torneo->id }}">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>

                        <!-- Pestañas de navegación -->
                        <ul class="nav nav-tabs mb-4" id="torneoTabs-{{ $torneo->id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="posiciones-tab-{{ $torneo->id }}" data-bs-toggle="tab" data-bs-target="#posiciones-{{ $torneo->id }}" type="button" role="tab" aria-controls="posiciones" aria-selected="true">
                                    <i class="fas fa-table"></i> Tablas de Posiciones
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="eliminatoria-tab-{{ $torneo->id }}" data-bs-toggle="tab" data-bs-target="#eliminatoria-{{ $torneo->id }}" type="button" role="tab" aria-controls="eliminatoria" aria-selected="false">
                                    <i class="fas fa-trophy"></i> Fase Eliminatoria
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="partidos-tab-{{ $torneo->id }}" data-bs-toggle="tab" data-bs-target="#partidos-{{ $torneo->id }}" type="button" role="tab" aria-controls="partidos" aria-selected="false">
                                    <i class="fas fa-futbol"></i> Todos los Partidos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="grupos-tab-{{ $torneo->id }}" data-bs-toggle="tab" data-bs-target="#grupos-{{ $torneo->id }}" type="button" role="tab" aria-controls="grupos" aria-selected="false">
                                    <i class="fas fa-users"></i> Grupos y Equipos
                                </button>
                            </li>
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="torneoTabsContent-{{ $torneo->id }}">
                            <!-- Pestaña de Tablas de Posiciones -->
                            <div class="tab-pane fade show active" id="posiciones-{{ $torneo->id }}" role="tabpanel" aria-labelledby="posiciones-tab-{{ $torneo->id }}">
                                <h3 class="mb-3">Tablas de Posiciones</h3>
                                @foreach($torneo->grupos as $grupo)
                                    <h4>{{ $grupo->nombre }}</h4>
                                    <table class="table table-striped mb-4">
                                        <thead>
                                            <tr>
                                                <th>Posición</th>
                                                <th>Equipo</th>
                                                <th>PJ</th>
                                                <th>PG</th>
                                                <th>PE</th>
                                                <th>PP</th>
                                                <th>GF</th>
                                                <th>GC</th>
                                                <th>DG</th>
                                                <th>PTS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tablasPosiciones[$torneo->id][$grupo->id] as $index => $estadisticas)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <img src="{{ asset($estadisticas['equipo']->logo) }}" alt="{{ $estadisticas['equipo']->nombre }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                                                        {{ $estadisticas['equipo']->nombre }}
                                                    </td>
                                                    <td>{{ $estadisticas['PJ'] }}</td>
                                                    <td>{{ $estadisticas['PG'] }}</td>
                                                    <td>{{ $estadisticas['PE'] }}</td>
                                                    <td>{{ $estadisticas['PP'] }}</td>
                                                    <td>{{ $estadisticas['GF'] }}</td>
                                                    <td>{{ $estadisticas['GC'] }}</td>
                                                    <td>{{ $estadisticas['DG'] }}</td>
                                                    <td>{{ $estadisticas['PTS'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>

                            <!-- Pestaña de Fase Eliminatoria -->
                            <div class="tab-pane fade" id="eliminatoria-{{ $torneo->id }}" role="tabpanel" aria-labelledby="eliminatoria-tab-{{ $torneo->id }}">
                                <h3 class="mb-3">Fase Eliminatoria</h3>
                                
                                @php
                                    // Obtener partidos de eliminatoria agrupados por fase
                                    $partidosEliminatoria = $torneo->partidos->where('tipo', 'eliminatoria')->groupBy('fase');
                                    
                                    // Definir el orden de las fases
                                    $ordenFases = [
                                        'Octavos de Final' => 1,
                                        'Cuartos de Final' => 2,
                                        'Semifinal' => 3,
                                        'Final' => 4
                                    ];
                                    
                                    // Ordenar las fases
                                    $fasesOrdenadas = $partidosEliminatoria->sortBy(function ($item, $key) use ($ordenFases) {
                                        return $ordenFases[$key] ?? 999;
                                    });
                                @endphp
                                
                                @if($fasesOrdenadas->count() > 0)
                                    <div class="tournament-bracket">
                                        <div class="row">
                                            @foreach($fasesOrdenadas as $fase => $partidos)
                                                <div class="col-md-3 bracket-round">
                                                    <h4 class="text-center mb-3">{{ $fase }}</h4>
                                                    @foreach($partidos->where('es_ida', true) as $partido)
                                                        <div class="bracket-match mb-4">
                                                            <div class="card">
                                                                <div class="card-header text-center">
                                                                    <small>{{ $partido->fecha->format('d/m/Y') }}</small>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <div class="team d-flex justify-content-between align-items-center mb-2">
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid me-2" style="max-height: 25px; max-width: 25px;">
                                                                            <span>{{ $partido->equipoLocal->nombre }}</span>
                                                                        </div>
                                                                        <span class="badge bg-light text-dark">{{ $partido->goles_local ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="team d-flex justify-content-between align-items-center">
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid me-2" style="max-height: 25px; max-width: 25px;">
                                                                            <span>{{ $partido->equipoVisitante->nombre }}</span>
                                                                        </div>
                                                                        <span class="badge bg-light text-dark">{{ $partido->goles_visitante ?? '-' }}</span>
                                                                    </div>
                                                                    
                                                                    @if($partido->partidosVuelta->count() > 0)
                                                                        @php
                                                                            $partidoVuelta = $partido->partidosVuelta->first();
                                                                        @endphp
                                                                        <hr class="my-2">
                                                                        <div class="text-center small">Vuelta: {{ $partidoVuelta->fecha->format('d/m/Y') }}</div>
                                                                        <div class="d-flex justify-content-between align-items-center small">
                                                                            <span>{{ $partidoVuelta->equipoLocal->nombre }}</span>
                                                                            <span>{{ $partidoVuelta->goles_local ?? '-' }} - {{ $partidoVuelta->goles_visitante ?? '-' }}</span>
                                                                            <span>{{ $partidoVuelta->equipoVisitante->nombre }}</span>
                                                                        </div>
                                                                        
                                                                        @if($partido->estado == 'finalizado' && $partidoVuelta->estado == 'finalizado')
                                                                            @php
                                                                                $resultadoGlobal = $partido->resultadoGlobal();
                                                                                $ganador = $partido->ganadorEliminatoria();
                                                                            @endphp
                                                                            @if($ganador)
                                                                                <div class="text-center mt-2">
                                                                                    <span class="badge bg-success">Avanza: {{ $ganador->nombre }}</span>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    
                                                                    <div class="text-center mt-2">
                                                                        <a href="{{ route('partidos.show', $partido) }}" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    
                                                    <!-- Partidos únicos (sin ida/vuelta) -->
                                                    @foreach($partidos->where('es_ida', null) as $partido)
                                                        <div class="bracket-match mb-4">
                                                            <div class="card">
                                                                <div class="card-header text-center">
                                                                    <small>{{ $partido->fecha->format('d/m/Y') }}</small>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <div class="team d-flex justify-content-between align-items-center mb-2">
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid me-2" style="max-height: 25px; max-width: 25px;">
                                                                            <span>{{ $partido->equipoLocal->nombre }}</span>
                                                                        </div>
                                                                        <span class="badge bg-light text-dark">{{ $partido->goles_local ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="team d-flex justify-content-between align-items-center">
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid me-2" style="max-height: 25px; max-width: 25px;">
                                                                            <span>{{ $partido->equipoVisitante->nombre }}</span>
                                                                        </div>
                                                                        <span class="badge bg-light text-dark">{{ $partido->goles_visitante ?? '-' }}</span>
                                                                    </div>
                                                                    
                                                                    @if($partido->estado == 'finalizado')
                                                                        <div class="text-center mt-2">
                                                                            @if($partido->goles_local > $partido->goles_visitante)
                                                                                <span class="badge bg-success">Ganador: {{ $partido->equipoLocal->nombre }}</span>
                                                                            @elseif($partido->goles_local < $partido->goles_visitante)
                                                                                <span class="badge bg-success">Ganador: {{ $partido->equipoVisitante->nombre }}</span>
                                                                            @else
                                                                                <span class="badge bg-warning">Empate</span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    <div class="text-center mt-2">
                                                                        <a href="{{ route('partidos.show', $partido) }}" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        No hay partidos de eliminatoria programados para este torneo.
                                    </div>
                                @endif
                            </div>

                            <!-- Pestaña de Todos los Partidos -->
                            <div class="tab-pane fade" id="partidos-{{ $torneo->id }}" role="tabpanel" aria-labelledby="partidos-tab-{{ $torneo->id }}">
                                <h3 class="mb-3">Partidos</h3>
                                <div class="mb-3">
                                    <a href="{{ route('partidos.create', ['torneo_id' => $torneo->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-plus"></i> Crear Nuevo Partido
                                    </a>
                                </div>
                                <table class="table partidosTable">
                                    <thead>
                                        <tr>
                                            <th>Local</th>
                                            <th>Visitante</th>
                                            <th>Fase</th>
                                            <th>Fecha</th>
                                            <th>Resultado</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($torneo->partidos as $partido)
                                            <tr>
                                                <td>
                                                    <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                                                    {{ $partido->equipoLocal->nombre }}
                                                </td>
                                                <td>
                                                    <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                                                    {{ $partido->equipoVisitante->nombre }}
                                                </td>
                                                <td>{{ $partido->fase }}</td>
                                                <td>{{ $partido->fecha->format('d/m/Y h:i A') }}</td>
                                                <td>
                                                    @if($partido->goles_local !== null && $partido->goles_visitante !== null)
                                                        {{ $partido->goles_local }} - {{ $partido->goles_visitante }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($partido->estado) }}</td>
                                                <td>
                                                    <a href="{{ route('partidos.show', $partido) }}" class="btn btn-outline-light btn-sm m-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @can('Editar Partidos')
                                                    <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary btn-sm m-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endcan
                                                    @can('Borrar Partidos')
                                                    <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm m-1 delete-partido" data-id="{{ $partido->id }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pestaña de Grupos y Equipos -->
                            <div class="tab-pane fade" id="grupos-{{ $torneo->id }}" role="tabpanel" aria-labelledby="grupos-tab-{{ $torneo->id }}">
                                <h3 class="mb-3">Grupos y Equipos</h3>
                                <div class="row">
                                    @foreach($torneo->grupos as $grupo)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-header">{{ $grupo->nombre }}</div>
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush">
                                                        @foreach($grupo->equipos as $equipo)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px;">
                                                                    {{ $equipo->nombre }}
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .tournament-bracket {
        overflow-x: auto;
    }
    
    .bracket-round {
        padding: 0 10px;
    }
    
    .bracket-match {
        position: relative;
    }
    
    .bracket-match::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -10px;
        width: 10px;
        height: 2px;
        background-color: #ccc;
        display: none;
    }
    
    .bracket-match::before {
        content: '';
        position: absolute;
        top: 50%;
        right: -10px;
        width: 2px;
        height: 50%;
        background-color: #ccc;
        display: none;
    }
    
    .bracket-round:not(:last-child) .bracket-match:nth-child(odd)::before {
        top: 50%;
        height: 50%;
        display: none;
    }
    
    .bracket-round:not(:last-child) .bracket-match:nth-child(even)::before {
        top: 0;
        height: 50%;
        display: none;
    }
    
    .bracket-round:not(:last-child) .bracket-match::after {
        display: none;
    }
    
    .team {
        margin-bottom: 5px;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.partidosTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "responsive": true,
            "order": [[3, "desc"]]
        });

        $('.delete-torneo').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-partido');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const partidoId = this.getAttribute('data-id');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    });
</script>
@endpush
