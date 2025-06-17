@extends('layouts.app')

@section('content')
<div class="container">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="mb-4 text-center">{{ $jugador->nombre }}</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-6 mb-6">
            <div class="card mb-2">
                <div class="card-header" style="background-color: {{ $jugador->equipo->color_primario }}; height: 8px;"></div>
                <div class="card-body">

                    
                    <div class="text-center mb-3">
                        <img src="{{ asset($jugador->foto) }}" alt="{{ $jugador->nombre }}" class="img-fluid rounded" style="max-width: 100px; max-height: 100px; ">
                        <h3 class="mt-2">{{ $jugador->nombre }}</h3>
                        <h5 class="mt-2">Información del Jugador:</h5>
                    </div>
                                        
                    <div class="text-center mb-2">
                        <span>Cédula:</span>
                        <span><strong>{{ $jugador->cedula }}</strong></span>
                    </div>
                    
                    <div class="text-center mb-2">
                        <span>Fecha de Nacimiento:</span>
                        <span><strong> {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->format('d/m/Y') : 'No establecida' }}</strong></span>
                    </div>

                    <div class="text-center mb-2">
                        <span>Edad:</span>
                        <span><strong>{{ $jugador->edad }} años</strong></span>
                    </div>

                    <div class="text-center mb-2">
                        <span>Dorsal:</span>
                        <span><strong>{{ $jugador->dorsal }}</strong></span>
                    </div>

                    <div class="text-center mb-2">
                        <span>Tipo:</span>
                        <span><strong>{{ ucfirst($jugador->tipo) }}</strong></span>
                    </div>

                </div>
                <div class="card-footer" style="background-color: {{ $jugador->equipo->color_secundario ?? '#f8f9fa' }}; height: 8px;"></div>
            </div>
        </div>

        <div class="col-md-6 mb-6">
            <div class="card mb-2">
                <div class="card-header" style="background-color: {{ $jugador->equipo->color_primario }}; height: 8px;"></div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset($jugador->equipo->logo) }}" alt="{{ $jugador->equipo->nombre }}" class="img-fluid" style="max-height: 100px;">
                        <h3 class="mt-2">{{ $jugador->equipo->nombre }}</h3>
                        <h5 class="mt-2">Estadisticas Globales:</h5>
                    </div>
                    
                    <div class="text-center mb-2">
                        <span>Goles:</span>
                        <span><strong>Proximamente...</strong></span>
                    </div>
                    
                    <div class="text-center mb-2">
                        <span>Tarjetas Amarillas:</span>
                        <span><strong>Proximamente...</strong></span>
                    </div>
                    
                    <div class="text-center mb-2">
                        <span>Tarjetas Rojas:</span>
                        <span><strong>Proximamente...</strong></span>
                    </div>
                </div>
                <div class="card-footer" style="background-color: {{ $jugador->equipo->color_secundario ?? '#f8f9fa' }}; height: 8px;"></div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mt-3">
                <a href="{{ route('jugador.index') }}" class="btn btn-sm btn-outline-secondary m-1">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
                @can('Editar Jugadores')
                <a href="{{ route('jugador.edit', $jugador) }}" class="btn btn-sm btn-outline-primary m-1">
                    <i class="fas fa-edit"></i> Editar
                </a>
                @endcan
                @can('Borrar Jugadores')
                <form action="{{ route('jugador.destroy', $jugador) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger m-1 delete-jugador" data-id="{{ $jugador->id }}">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.delete-jugador').click(function(e) {
        e.preventDefault();
        const jugadorId = $(this).data('id');
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
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endpush
