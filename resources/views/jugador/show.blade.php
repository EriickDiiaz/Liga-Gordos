@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">{{ $jugador->nombre }}</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="border-color: {{ $jugador->equipo->color_primario }}; background: {{ $jugador->equipo->color_primario }};"></div>
                <div class="card-body position-relative">
                    <!-- Team logo as background -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="z-index: 0; opacity: 0.3;">
                        <img src="{{ asset($jugador->equipo->logo) }}" alt="{{ $jugador->equipo->nombre }}" class="img-fluid" style="max-width: 80%; max-height: 80%; object-fit: contain;">
                    </div>
                    
                    <!-- Player content -->
                    <div class="position-relative" style="z-index: 1;">
                        <div class="text-center mb-4">
                        <img src="{{ asset($jugador->foto) }}" class="card-img-top mt-3" alt="{{ $jugador->nombre }}" class="img-fluid rounded" style="max-width: 300px;">
                            <!--@if($jugador->foto)
                                <img src="{{ asset('storage/' . $jugador->foto) }}" alt="{{ $jugador->nombre }}" class="img-fluid rounded" style="max-height: 300px;">
                            @else
                                <img src="{{ asset('img/default-player.png') }}" alt="Default" class="img-fluid rounded" style="max-height: 300px;">
                            @endif-->
                        </div>
                        <h4 class="card-title">Información del Jugador</h4>
                        <p><strong>Nombre:</strong> {{ $jugador->nombre }}</p>
                        <p><strong>Cédula:</strong> {{ $jugador->cedula }}</p>
                        <p><strong>Fecha de Nacimiento:</strong> {{ $jugador->fecha_nacimiento ? $jugador->fecha_nacimiento->format('d/m/Y') : 'No establecida' }}</p>
                        <p><strong>Edad:</strong> {{ $jugador->edad }} años</p>
                        <p><strong>Dorsal:</strong> {{ $jugador->dorsal }}</p>
                        <p><strong>Tipo:</strong> {{ ucfirst($jugador->tipo) }}</p>
                        <p><strong>Equipo:</strong> {{ $jugador->equipo->nombre }}</p>
                    </div>
                </div>
                <div class="card-footer" style="border-color: {{ $jugador->equipo->color_secundario }}; background: {{ $jugador->equipo->color_secundario }};"></div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('jugador.index') }}" class="btn btn-outline-secondary m-1">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
                @can('editar jugadores')
                <a href="{{ route('jugador.edit', $jugador) }}" class="btn btn-outline-primary m-1">
                    <i class="fas fa-edit"></i> Editar
                </a>
                @endcan
                @can('borrar jugadores')
                <form action="{{ route('jugador.destroy', $jugador) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger m-1 delete-jugador" data-id="{{ $jugador->id }}">
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
