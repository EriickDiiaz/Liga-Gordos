@extends('layouts.app')

@section('content')
<div class="container">
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1 class="mb-3">Patrocinadores</h1>
    
    <div class="row mb-3">
        <div class="col-md-6">
            @can('Crear Patrocinadores')
            <a href="{{ route('patrocinador.create') }}" class="btn btn-outline-success">
                <i class="fas fa-plus"></i> Crear Nuevo Patrocinador
            </a>
            @endcan
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="search-input" class="form-control" placeholder="Buscar patrocinadores...">
            </div>
        </div>
    </div>
    
    <div id="patrocinadores-container" class="row row-cols-1 row-cols-md-4 g-4">
        @foreach($patrocinadores->sortBy('nombre') as $patrocinador)
            <div class="col patrocinador-item">
                <div class="card h-100">
                    <img src="{{ asset($patrocinador->logo) }}" class="card-img-top mt-2" alt="{{ $patrocinador->nombre }}" style="height: 150px; object-fit: contain;">
                    <div class="card-body text-center">                        
                        <h5 class="card-title">{{ $patrocinador->nombre }}</h5>
                        <div class="d-flex justify-content-center align-items-center">
                            @if($patrocinador->instagram)
                                <a href="{{ $patrocinador->instagram }}" target="blank" class="mx-2">
                                    <i class="fa-brands fa-instagram" style="font-size: 1.5rem;"></i>
                                </a>
                            @endif
                            @if($patrocinador->tiktok)
                                <a href="{{ $patrocinador->tiktok }}" target="blank" class="mx-2">
                                    <i class="fa-brands fa-tiktok" style="font-size: 1.5rem;"></i>
                                </a>
                            @endif
                            @if($patrocinador->facebook)
                                <a href="{{ $patrocinador->facebook }}" target="blank" class="mx-2">
                                    <i class="fa-brands fa-square-facebook" style="font-size: 1.5rem;"></i>
                                </a>
                            @endif
                            @if($patrocinador->telefono)
                                <a href="https://wa.me/{{ $patrocinador->telefono }}" target="blank" class="mx-2">
                                    <i class="fa-brands fa-whatsapp" style="font-size: 1.5rem;"></i>
                                </a>
                            @endif
                        </div>                  
                        <div class="text-center mt-2">
                            @auth
                                @can('Editar Patrocinadores')
                                    <a href="{{ route('patrocinador.edit', $patrocinador) }}" class="btn btn-outline-primary btn-sm m-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endcan
                                @can('Borrar Patrocinadores')
                                    <form action="{{ route('patrocinador.destroy', $patrocinador) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm m-1 delete-patrocinador" data-id="{{ $patrocinador->id }}">
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
        ¡Uy! No se encontraron patrocinadores.
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de búsqueda
        const searchInput = document.getElementById('search-input');
        const patrocinadoresContainer = document.getElementById('patrocinadores-container');
        const patrocinadorItems = document.querySelectorAll('.patrocinador-item');
        const noResults = document.getElementById('no-results');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let hasResults = false;
            
            patrocinadorItems.forEach(item => {
                const patrocinadorNombre = item.querySelector('.card-title').textContent.toLowerCase();
                
                if (patrocinadorNombre.includes(searchTerm)) {
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
        const deleteButtons = document.querySelectorAll('.delete-patrocinador');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const patrocinadoresId = this.getAttribute('data-id');
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

