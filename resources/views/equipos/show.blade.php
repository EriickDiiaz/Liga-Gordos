@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $equipo->nombre }}</h1>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <h5>Colores del Equipo</h5>
                    <p>
                        Color Primario: <span style="color: {{ $equipo->color_primario }};">■</span> {{ $equipo->color_primario }}
                    </p>
                    @if($equipo->color_secundario)
                        <p>
                            Color Secundario: <span style="color: {{ $equipo->color_secundario }};">■</span> {{ $equipo->color_secundario }}
                        </p>
                    @endif
                    <h5>Estado</h5>
                    <p>
                        <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                            {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary">Volver a la lista</a>    
        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary">Editar</a>
    </div>
</div>
@endsection