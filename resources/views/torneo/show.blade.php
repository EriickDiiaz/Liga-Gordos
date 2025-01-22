@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $torneo->nombre }}</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detalles del Torneo</h5>
            <p><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
            <p><strong>Fecha de Inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($torneo->estado) }}</p>
        </div>
    </div>

    <div class="mt-4">
        <h2>Equipos Participantes</h2>
        <!-- Aquí irá la lista de equipos participantes -->
    </div>

    <div class="mt-4">
        <h2>Partidos</h2>
        <!-- Aquí irá la lista de partidos o el bracket del torneo -->
    </div>

    <div class="mt-4">
        <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-primary">Editar Torneo</a>
        <a href="{{ route('torneos.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
</div>
@endsection