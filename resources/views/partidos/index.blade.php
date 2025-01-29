@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Partidos</h1> 
    
    <a href="{{ route('partidos.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Partido</a>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($partidos as $partido)
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h3 class="card-title">{{ $partido->torneo->nombre }}</h3>
                        <h5 class="card-subtitle mb-2 text-muted">{{ $partido->fase }} - Grupo {{ $partido->grupo->nombre ?? 'Sin grupo' }}</h5>
                        <div class="d-flex my-3">
                            <div class="col-4">
                                <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 100px;">
                                <h6 class="mt-2">{{ $partido->equipoLocal->nombre }}</h6>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <h1 class="mx-2">VS</h1>
                            </div>
                            <div class="col-4">
                                <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 100px;">
                                <h6 class="mt-2">{{ $partido->equipoVisitante->nombre }}</h6>
                            </div>
                        </div>
                        <span class="badge bg-{{ $partido->estado == 'programado' ? 'primary' : ($partido->estado == 'en_curso' ? 'success' : 'secondary') }}">
                            {{ ucfirst($partido->estado) }}
                        </span>
                        <h5 class="mt-3">{{ $partido->fecha->format('d/m/Y h:i A') }}</h5>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('partidos.show', $partido) }}" class="btn btn-outline-light m-1">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary m-1">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger m-1 delete-partido" data-id="{{ $partido->id }}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-partido');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const partidoId = this.getAttribute('data-id');
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
                        this.closest('form').submit();
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection

