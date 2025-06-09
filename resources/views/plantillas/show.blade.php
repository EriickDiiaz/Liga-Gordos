@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Plantilla de {{ $equipo->nombre }} - {{ $torneo->nombre }}</h1>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Información del equipo -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header" style="background-color: {{ $equipo->color_primario }}; height: 8px;"></div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid" style="max-height: 100px;">
                        <h3 class="mt-2">{{ $equipo->nombre }}</h3>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jugadores inscritos:</span>
                        <span><strong>{{ $jugadoresInscritos->count() }}</strong> / 15</span>
                    </div>
                    
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ ($jugadoresInscritos->count() / 15) * 100 }}%;" 
                             aria-valuenow="{{ $jugadoresInscritos->count() }}" 
                             aria-valuemin="0" 
                             aria-valuemax="15">
                            {{ $jugadoresInscritos->count() }}/15
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Porteros:</span>
                        <span><strong>{{ $jugadoresInscritos->where('tipo', 'portero')->count() }}</strong></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jugadores habilidosos:</span>
                        <span><strong>{{ $jugadoresInscritos->where('tipo', 'habilidoso')->count() }}</strong></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Jugadores con brazalete:</span>
                        <span><strong>{{ $jugadoresInscritos->where('tipo', 'brazalete')->count() }}</strong></span>
                    </div>
                </div>
                <div class="card-footer" style="background-color: {{ $equipo->color_secundario ?? '#f8f9fa' }}; height: 8px;"></div>
            </div>
        </div>
        
        <!-- Jugadores inscritos -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Jugadores Inscritos</h3>
                </div>
                <div class="card-body">
                    @if($jugadoresInscritos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="jugadoresInscritosTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Estadísticas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jugadoresInscritos as $jugador)
                                        <tr>
                                            <td>{{ $jugador->dorsal }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($jugador->foto)
                                                        <img src="{{ asset($jugador->foto) }}" alt="{{ $jugador->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px; border-radius: 50%;">
                                                    @endif
                                                    {{ $jugador->nombre }}
                                                </div>
                                            </td>
                                            <td>{{ ucfirst($jugador->tipo) }}</td>
                                            <td>
                                                @php
                                                    $stats = $jugador->torneos->where('id', $torneo->id)->first()->pivot;
                                                @endphp
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#estadisticasModal{{ $jugador->id }}">
                                                    <i class="fas fa-chart-bar"></i> 
                                                    G: {{ $stats->goles }} | 
                                                    A: {{ $stats->tarjetas_amarillas }} | 
                                                    R: {{ $stats->tarjetas_rojas }}
                                                    @if($jugador->tipo == 'portero')
                                                     | PI: {{ $stats->porterias_imbatidas }}
                                                    @endif
                                                </button>
                                                
                                                <!-- Modal de Estadísticas -->
                                                <div class="modal fade" id="estadisticasModal{{ $jugador->id }}" tabindex="-1" aria-labelledby="estadisticasModalLabel{{ $jugador->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="estadisticasModalLabel{{ $jugador->id }}">Estadísticas de {{ $jugador->nombre }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('plantillas.estadisticas', [$torneo->id, $equipo->id, $jugador->id]) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="goles{{ $jugador->id }}" class="form-label">Goles</label>
                                                                        <input type="number" class="form-control" id="goles{{ $jugador->id }}" name="goles" value="{{ $stats->goles }}" min="0">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="tarjetas_amarillas{{ $jugador->id }}" class="form-label">Tarjetas Amarillas</label>
                                                                        <input type="number" class="form-control" id="tarjetas_amarillas{{ $jugador->id }}" name="tarjetas_amarillas" value="{{ $stats->tarjetas_amarillas }}" min="0">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="tarjetas_rojas{{ $jugador->id }}" class="form-label">Tarjetas Rojas</label>
                                                                        <input type="number" class="form-control" id="tarjetas_rojas{{ $jugador->id }}" name="tarjetas_rojas" value="{{ $stats->tarjetas_rojas }}" min="0">
                                                                    </div>
                                                                    @if($jugador->tipo == 'portero')
                                                                        <div class="mb-3">
                                                                            <label for="porterias_imbatidas{{ $jugador->id }}" class="form-label">Porterías Imbatidas</label>
                                                                            <input type="number" class="form-control" id="porterias_imbatidas{{ $jugador->id }}" name="porterias_imbatidas" value="{{ $stats->porterias_imbatidas }}" min="0">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="{{ route('plantillas.quitar', [$torneo->id, $equipo->id, $jugador->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger quitar-jugador" data-id="{{ $jugador->id }}">
                                                        <i class="fas fa-user-minus"></i> Quitar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No hay jugadores inscritos en este torneo.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Jugadores disponibles -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Jugadores Disponibles</h3>
        </div>
        <div class="card-body">
            @if($jugadoresDisponibles->count() > 0)
                @if($limiteAlcanzado)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Se ha alcanzado el límite de 15 jugadores para este torneo.
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-striped" id="jugadoresDisponiblesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Edad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jugadoresDisponibles as $jugador)
                                <tr>
                                    <td>{{ $jugador->dorsal }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($jugador->foto)
                                                <img src="{{ asset($jugador->foto) }}" alt="{{ $jugador->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px; border-radius: 50%;">
                                            @endif
                                            {{ $jugador->nombre }}
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($jugador->tipo) }}</td>
                                    <td>{{ $jugador->edad }} años</td>
                                    <td>
                                        <form action="{{ route('plantillas.agregar', [$torneo->id, $equipo->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="jugador_id" value="{{ $jugador->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-success" {{ $limiteAlcanzado ? 'disabled' : '' }}>
                                                <i class="fas fa-user-plus"></i> Agregar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Todos los jugadores del equipo ya están inscritos en este torneo.
                </div>
            @endif
        </div>
    </div>
    
    <div class="text-center mt-3">
        <a href="{{ route('plantillas.index', $torneo->id) }}" class="btn btn-outline-secondary m-1">
            <i class="fas fa-arrow-left"></i> Volver a Equipos
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#jugadoresInscritosTable, #jugadoresDisponiblesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "responsive": true,
            "paging": true,
            "searching": true,
            "info": false,
            "order": [[0, "asc"]]
        });

        $('.quitar-jugador').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres quitar este jugador de la plantilla del torneo?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
