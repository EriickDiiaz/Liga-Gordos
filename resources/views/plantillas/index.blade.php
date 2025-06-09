@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Plantillas del Torneo: {{ $torneo->nombre }}</h1>
    
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
    
    <div class="card mb-4">
        <div class="card-header">
            <h2>Equipos Participantes</h2>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($torneo->equipos as $equipo)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-header" style="background-color: {{ $equipo->color_primario }}; height: 8px;"></div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid me-3" style="max-height: 50px; max-width: 50px;">
                                    <h5 class="card-title mb-0">{{ $equipo->nombre }}</h5>
                                </div>
                                
                                @php
                                    $jugadoresInscritos = $equipo->jugadoresEnTorneo($torneo->id)->count();
                                    $totalJugadores = $equipo->jugadores->count();
                                    $porcentaje = $totalJugadores > 0 ? ($jugadoresInscritos / $totalJugadores) * 100 : 0;
                                @endphp
                                
                                <p class="card-text">
                                    <strong>Jugadores inscritos:</strong> {{ $jugadoresInscritos }} / {{ $totalJugadores }}
                                </p>
                                
                                <div class="progress mb-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $porcentaje }}%;" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">{{ round($porcentaje) }}%</div>
                                </div>
                                
                                <a href="{{ route('plantillas.show', [$torneo->id, $equipo->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-users"></i> Gestionar Plantilla
                                </a>
                            </div>
                            <div class="card-footer" style="background-color: {{ $equipo->color_secundario ?? '#f8f9fa' }}; height: 8px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="text-center mt-3">
        <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary m-1">
            <i class="fas fa-arrow-left"></i> Volver al Torneo
        </a>
    </div>
</div>
@endsection
