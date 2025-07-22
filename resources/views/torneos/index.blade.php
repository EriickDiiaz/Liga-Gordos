@extends('layouts.app')

@section('content')
<div class="container">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fa-solid fa-trophy me-2"></i>Torneos</h1>
        @can('Crear Torneos')
        <a href="{{ route('torneos.create') }}" class="btn btn-outline-success">
            <i class="fas fa-plus"></i> Crear Nuevo Torneo
        </a>
        @endcan
    </div>

    <div class="row">
        @forelse($torneos as $torneo)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-warning text-dark text-center">
                        <h4 class="card-title mb-2 text-center">
                            <i class="fa-solid fa-trophy me-2"></i>
                            {{ $torneo->nombre }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
                        <p class="mb-1"><strong>Estado:</strong> <span class="badge bg-primary">{{ ucfirst($torneo->estado) }}</span></p>
                        <p class="mb-1"><strong>Equipos:</strong> {{ $torneo->equipos->count() }}</p>
                        <p class="mb-1"><strong>Fecha de inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div class="card-footer border-warning text-center">
                        <a href="{{ route('torneos.show', $torneo) }}" class="btn btn-outline-light btn-sm me-2">
                            <i class="fas fa-eye me-2"></i>Ver
                        </a>
                        @can('Editar Torneos')
                        <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        @endcan
                        @can('Borrar Torneos')
                        <form action="{{ route('torneos.destroy', $torneo) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm delete-torneo me-2" data-id="{{ $torneo->id }}">
                                <i class="fas fa-trash-alt me-2"></i>Eliminar
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No hay torneos registrados.</div>
            </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete-torneo').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
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
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
