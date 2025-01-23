@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalles del Partido</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $partido->equipoLocal->nombre }} vs {{ $partido->equipoVisitante->nombre }}</h5>
            <p><strong>Torneo:</strong> {{ $partido->torneo->nombre }}</p>
            <p><strong>Grupo:</strong> {{ $partido->grupo->nombre }}</p>
            <p><strong>Fecha:</strong> {{ $partido->fecha->format('d/m/Y H:i') }}</p>
            <p><strong>Tipo:</strong> {{ ucfirst($partido->tipo) }}</p>
            <p><strong>Fase:</strong> {{ $partido->fase ?? 'N/A' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($partido->estado) }}</p>
        </div>
    </div>

    <h2>Acciones del Partido</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Jugador</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partido->acciones as $accion)
                <tr>
                    <td>{{ $accion->jugador->nombre }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $accion->tipo_accion)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Registrar Nueva Acción</h3>
    <form action="{{ route('partidos.registrar-accion', $partido) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="jugador_id" class="form-label">Jugador</label>
            <select name="jugador_id" id="jugador_id" class="form-control" required>
                <option value="">Seleccione un jugador</option>
                @foreach($partido->equipoLocal->jugadores as $jugador)
                    <option value="{{ $jugador->id }}">{{ $jugador->nombre }} ({{ $partido->equipoLocal->nombre }})</option>
                @endforeach
                @foreach($partido->equipoVisitante->jugadores as $jugador)
                    <option value="{{ $jugador->id }}">{{ $jugador->nombre }} ({{ $partido->equipoVisitante->nombre }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tipo_accion" class="form-label">Tipo de Acción</label>
            <select name="tipo_accion" id="tipo_accion" class="form-control" required>
                <option value="gol">Gol</option>
                <option value="tarjeta_amarilla">Tarjeta Amarilla</option>
                <option value="tarjeta_roja">Tarjeta Roja</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Acción</button>
    </form>

    <div class="mt-4">
        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-primary">Editar Partido</a>
        <a href="{{ route('partidos.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
</div>
@endsection

