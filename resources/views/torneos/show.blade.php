{{-- filepath: c:\xampp\htdocs\liga-gordos\resources\views\torneos\show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1><i class="fa-solid fa-trophy me-2"></i>{{ $torneo->nombre }}</h1>

    {{-- Detalles del torneo --}}
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Detalles del Torneo</h5>
            <p><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
            <p><strong>Fecha de Inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($torneo->estado) }}</p>
            <p><strong>Equipos participantes:</strong> {{ $torneo->equipos->count() }}</p>
            
            @can('Inscribir Jugadores')
            <h5>Acciones</h5>
            <a href="{{ route('plantillas.index', $torneo->id) }}" class="btn btn-outline-success m-1">
                <i class="fas fa-user-plus"></i> Inscribir Jugadores
            </a>  
            @endcan                                
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

    {{-- Tablas de posiciones por grupo --}}
    <h2 class="mt-4"><i class="fa-solid fa-list-ul me-2"></i>Tablas de Posiciones</h2>
    @foreach($torneo->grupos as $grupo)
        <h4>{{ $grupo->nombre }}</h4>
        <table class="table table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th>Pos</th>
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
                @foreach($tablasPosiciones[$grupo->id] as $index => $estadisticas)
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

    {{-- Estadísticas individuales --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <h3><i class="fas fa-futbol me-2"></i>Máximos Goleadores</h3>
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Goles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goleadores as $goleador)
                        <tr>
                            <td>{{ $goleador->jugador->nombre }}</td>
                            <td>{{ $goleador->jugador->equipo->nombre }}</td>
                            <td>{{ $goleador->goles }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3><i class="fas fa-solid fa-hand-sparkles me-2"></i>Porterías Imbatidas</h3>
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Portero</th>
                        <th>Equipo</th>
                        <th>Imbatidas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($porteriasImbatidas as $portero)
                        <tr>
                            <td>{{ $portero->jugador->nombre }}</td>
                            <td>{{ $portero->jugador->equipo->nombre }}</td>
                            <td>{{ $portero->porterias_imbatidas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <h3><i class="fas fa-solid fa-mobile-button text-warning me-2"></i>Tarjetas Amarillas</h3>
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Amarillas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($amarillas as $jugador)
                        <tr>
                            <td>{{ $jugador->jugador->nombre }}</td>
                            <td>{{ $jugador->jugador->equipo->nombre }}</td>
                            <td>{{ $jugador->tarjetas_amarillas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3><i class="fas fa-solid fa-mobile-button text-danger me-2"></i>Tarjetas Rojas</h3>
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Rojas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rojas as $jugador)
                        <tr>
                            <td>{{ $jugador->jugador->nombre }}</td>
                            <td>{{ $jugador->jugador->equipo->nombre }}</td>
                            <td>{{ $jugador->tarjetas_rojas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
 

    {{-- Próximos partidos --}}
    <h2 class="mt-4"><i class="fa-solid fa-arrows-up-to-line me-2"></i>Próximos Partidos</h2>
    <table class="table table-hover table-striped table-sm" id="partidosTable">
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
                    <td>{{ $partido->fecha->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($partido->goles_local !== null && $partido->goles_visitante !== null)
                            {{ $partido->goles_local }} - {{ $partido->goles_visitante }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ ucfirst($partido->estado) }}</td>
                    <td>
                        @can('Editar Partidos')
                            <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endcan
                        @can('Borrar Partidos')
                            <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        @endcan                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Gestión de grupos y equipos --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <h2 class="text-center"><i class="fa-solid fa-users-rectangle me-2"></i>Grupos</h2>
            @can('Editar Torneos')
            <form action="{{ route('torneos.addGroup', $torneo) }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del grupo" required>
                    <button type="submit" class="btn btn-outline-primary">Agregar Grupo</button>
                </div>
            </form>
            @endcan
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($torneo->grupos as $grupo)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-header">{{ $grupo->nombre }}</div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @foreach($grupo->equipos as $equipo)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px;">
                                                {{ $equipo->nombre }}
                                            </div>
                                            @can('Editar Torneos')
                                            <form action="{{ route('torneos.removeEquipo', [$torneo, $equipo]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @can('Editar Torneos')
            <div class="col-md-6">
                <h2 class="text-center"><i class="fa-solid fa-user-plus me-2"></i>Agregar Equipo a Grupo</h2>
                <form action="{{ route('torneos.addEquipo', $torneo) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label for="equipo_id" class="form-label">Equipo</label>
                        <select name="equipo_id" id="equipo_id" class="form-select" required>
                            @foreach(App\Models\Equipo::all() as $equipo)
                                <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grupo_id" class="form-label">Grupo</label>
                        <select name="grupo_id" id="grupo_id" class="form-select" required>
                            @foreach($torneo->grupos as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Agregar Equipo al Grupo</button>
                </form>
            </div>
        @endcan 
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#partidosTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "responsive": true,
            "order": [[3, "desc"]]
        });
    });
</script>
@endpush