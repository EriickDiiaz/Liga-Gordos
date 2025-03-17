@extends('layouts.app')

@section('content')
<div class="container">
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1 class="mb-3">Equipos</h1>
    
    <div class="row mb-3">
        <div class="col-md-6">
            @can('Crear Equipos')
            <a href="{{ route('equipos.create') }}" class="btn btn-outline-success">
                <i class="fas fa-plus"></i> Crear Nuevo Equipo
            </a>
            @endcan
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="search-input" class="form-control" placeholder="Buscar equipos...">
            </div>
        </div>
    </div>
    
    <div id="equipos-container" class="row row-cols-1 row-cols-md-4 g-4">
        @foreach($equipos->sortBy('nombre') as $equipo)
            <div class="col equipo-item">
                <div class="card h-100">
                    <img src="{{ asset($equipo->logo) }}" class="card-img-top mt-2" alt="{{ $equipo->nombre }}" style="height: 150px; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $equipo->nombre }}
                            <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                                {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </h5>
                        <p class="card-text mb-1 small">
                            Color Primario: <span style="color: {{ $equipo->color_primario }};">■</span>
                            @if($equipo->color_secundario)
                                Color Secundario: <span style="color: {{ $equipo->color_secundario }};">■</span>
                            @endif
                        </p>
                        
                        <p class="mb-1 small">Jugadores Habilidosos: {{ $equipo->jugadores->where('tipo', 'habilidoso')->count() }}</p>
                        <p class="mb-1 small">Jugadores con Brazalete: {{ $equipo->jugadores->where('tipo', 'brazalete')->count() }}</p>
                        <p class="mb-1 small">Total de Jugadores: {{ $equipo->jugadores->count() }}</p>
                        <div class="text-center mt-2">
                            <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-outline-light btn-sm m-1">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            @auth
                                @can('Editar Equipos')
                                    <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary btn-sm m-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endcan
                                @can('Borrar Equipos')
                                    <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm m-1 delete-equipo" data-id="{{ $equipo->id }}">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan
                            @endauth                            
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Mensaje de No Resultados -->
    <div id="no-results" class="alert alert-warning mt-3 d-none">
        ¡Uy! No se encontraron equipos.
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda
        const searchInput = document.getElementById('search-input');
        const equiposContainer = document.getElementById('equipos-container');
        const equipoItems = document.querySelectorAll('.equipo-item');
        const noResults = document.getElementById('no-results');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let hasResults = false;
            
            equipoItems.forEach(item => {
                const equipoNombre = item.querySelector('.card-title').textContent.toLowerCase();
                
                if (equipoNombre.includes(searchTerm)) {
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
        const deleteButtons = document.querySelectorAll('.delete-equipo');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const equipoId = this.getAttribute('data-id');
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

