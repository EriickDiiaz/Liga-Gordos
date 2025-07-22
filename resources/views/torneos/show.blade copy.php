@extends('layouts.app')

@section('content')
<div class="container">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1 class="mb-3">{{ $torneo->nombre }}</h1>
    
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Detalles del Torneo</h5>
            <p><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
            <p><strong>Fecha de Inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($torneo->estado) }}</p>
            <p><strong>Equipos participantes:</strong> {{ $torneo->equipos->count() }}</p>
        </div>
    </div>

    <h2 class="mb-3">Tablas de Posiciones</h2>
    @foreach($torneo->grupos as $grupo)
        <h3>{{ $grupo->nombre }}</h3>
        <table class="table table-striped">
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

    <h2 class="mt-3 text-center">Partidos</h2>
    <a href="{{ route('partidos.create', $torneo) }}" class="btn btn-outline-success mb-3">Crear Nuevo Partido</a>
    <table class="table" id="partidosTable">
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
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-4"> 
        <div class="col-md-6">
            <h2 class="text-center">Grupos</h2>
            <form action="{{ route('torneos.addGroup', $torneo) }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del grupo" required>
                    <button type="submit" class="btn btn-outline-primary">Agregar Grupo</button>
                </div>
            </form>
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
                                            <form action="{{ route('torneos.removeEquipo', [$torneo, $equipo]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">Agregar Equipo a Grupo</h2>
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

