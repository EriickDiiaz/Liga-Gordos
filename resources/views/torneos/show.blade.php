@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $torneo->nombre }}</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Detalles del Torneo</h5>
            <p><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
            <p><strong>Fecha de Inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($torneo->estado) }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h2>Grupos</h2>
            <form action="{{ route('torneos.addGroup', $torneo) }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del grupo" required>
                    <button type="submit" class="btn btn-primary">Agregar Grupo</button>
                </div>
            </form>
            @foreach($torneo->grupos as $grupo)
                <div class="card mb-3">
                    <div class="card-header">{{ $grupo->nombre }}</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($grupo->equipos as $equipo)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $equipo->nombre }}
                                    <form action="{{ route('torneos.removeEquipo', [$torneo, $equipo]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-6">
            <h2>Equipos</h2>
            <form action="{{ route('torneos.addEquipo', $torneo) }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <select name="equipo_id" class="form-control" required>
                        @foreach(App\Models\Equipo::all() as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                    <select name="grupo_id" class="form-control">
                        <option value="">Sin grupo</option>
                        @foreach($torneo->grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Agregar Equipo</button>
                </div>
            </form>
            <ul class="list-group">
                @foreach($torneo->equipos as $equipo)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $equipo->nombre }}
                        <span>
                            {{ $equipo->pivot->grupo_id ? 'Grupo: ' . $torneo->grupos->find($equipo->pivot->grupo_id)->nombre : 'Sin grupo' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <h2 class="mt-4">Partidos</h2>
    <a href="{{ route('partidos.create', $torneo) }}" class="btn btn-success mb-3">Crear Nuevo Partido</a>
    <table class="table">
        <thead>
            <tr>
                <th>Local</th>
                <th>Visitante</th>
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($torneo->partidos as $partido)
                <tr>
                    <td>{{ $partido->equipoLocal->nombre }}</td>
                    <td>{{ $partido->equipoVisitante->nombre }}</td>
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
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-primary btn-sm">Editar</a>
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

