@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">{{ $jugador->nombre }}</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($jugador->foto)
                            <img src="{{ asset('storage/' . $jugador->foto) }}" alt="{{ $jugador->nombre }}" class="img-fluid rounded" style="max-height: 300px;">
                        @else
                            <img src="{{ asset('img/default-player.png') }}" alt="Default" class="img-fluid rounded" style="max-height: 300px;">
                        @endif
                    </div>
                    <h5 class="card-title">Información del Jugador</h5>
                    <p><strong>Nombre:</strong> {{ $jugador->nombre }}</p>
                    <p><strong>Cédula:</strong> {{ $jugador->cedula }}</p>
                    <p><strong>Fecha de Nacimiento:</strong> {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->format('d/m/Y') : 'No establecida' }}</p>
                    <p><strong>Edad:</strong> {{ $jugador->edad }} años</p>
                    <p><strong>Dorsal:</strong> {{ $jugador->dorsal }}</p>
                    <p><strong>Tipo:</strong> {{ ucfirst($jugador->tipo) }}</p>
                    <p><strong>Equipo:</strong> {{ $jugador->equipo_nombre }}</p>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('jugador.index') }}" class="btn btn-outline-secondary m-1">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>    
                <a href="{{ route('jugador.edit', $jugador) }}" class="btn btn-outline-primary m-1">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

