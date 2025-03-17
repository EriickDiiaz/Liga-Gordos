@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Partidos</h1> 

    <div class="row mb-3">
        <div class="col-md-6">
            @can('Crear Partidos')
            <a href="{{ route('partidos.create') }}" class="btn btn-outline-success">
                <i class="fas fa-plus"></i> Crear Nuevo Partido
            </a>
            @endcan
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="search-input" class="form-control" placeholder="Buscar partidos...">
            </div>
        </div>
    </div>

    <div id="partidos-container" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($partidos as $partido)
            <div class="col partido-item">
                <div class="card h-100 text-center">
                    <div class="card-body p-2">
                        <h5 class="card-title">{{ $partido->torneo->nombre }}</h5>
                        <p class="card-subtitle mb-2 text-muted small">{{ $partido->fase }} - {{ $partido->grupo->nombre ?? 'Sin grupo' }}</p>
                        <div class="d-flex my-2">
                            <div class="col-4">
                                <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 60px;">
                                <p class="mt-1 mb-0 small">{{ $partido->equipoLocal->nombre }}</p>
                                <h4>{{ $partido->goles_local ?? 0 }}</h4>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <h5 class="mx-2">VS</h5>
                            </div>
                            <div class="col-4">
                                <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 60px;">
                                <p class="mt-1 mb-0 small">{{ $partido->equipoVisitante->nombre }}</p>
                                <h4>{{ $partido->goles_visitante ?? 0 }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-{{ $partido->estado == 'programado' ? 'primary' : ($partido->estado == 'en_curso' ? 'success' : 'secondary') }}">
                            {{ ucfirst($partido->estado) }}
                        </span>
                        <p class="mt-2 mb-0 small">{{ $partido->fecha->format('d/m/Y h:i A') }}</p>
                    </div>
                    <div class="card-footer p-2">
                        <a href="{{ route('partidos.show', $partido) }}" class="btn btn-outline-light btn-sm m-1">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        @can('Editar Partidos')
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary btn-sm m-1">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @endcan
                        @can('Borrar Partidos')
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm m-1 delete-partido" data-id="{{ $partido->id }}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Mensaje de No Resultados -->
    <div id="no-results" class="alert alert-info mt-3 d-none">
        ¡Uy! No se encontraron partidos.
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda
        const searchInput = document.getElementById('search-input');
        const partidosContainer = document.getElementById('partidos-container');
        const partidoItems = document.querySelectorAll('.partido-item');
        const noResults = document.getElementById('no-results');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let hasResults = false;
            
            partidoItems.forEach(item => {
                const cardContent = item.textContent.toLowerCase();
                
                if (cardContent.includes(searchTerm)) {
                    item.classList.remove('d-none');
                    hasResults = true;
                } else {
                    item.classList.add('d-none');
                }
            });
            
            // Mostrar mensaje si no hay resultados
            if (hasResults || searchTerm === '') {
                noResults.classList.add('d-none');
            } else {
                noResults.classList.remove('d-none');
            }
        });
        
        // Confirmación de eliminación
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

