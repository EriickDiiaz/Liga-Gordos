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
    @can('Crear Equipos')
    <a href="{{ route('equipos.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Equipo</a>
    @endcan
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($equipos as $equipo)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ asset($equipo->logo) }}" class="card-img-top mt-3" alt="{{ $equipo->nombre }}" style="height: 200px; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $equipo->nombre }}
                            <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                                {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </h5>
                        <p class="card-text mb-1">
                            Color Primario: <span style="color: {{ $equipo->color_primario }};">■</span>
                            @if($equipo->color_secundario)
                                Color Secundario: <span style="color: {{ $equipo->color_secundario }};">■</span>
                            @endif
                        </p>
                        
                        <p class="mb-1">Jugadores Habilidosos: {{ $equipo->jugadores->where('tipo', 'habilidoso')->count() }}</p>
                        <p class="mb-1">Jugadores con Brazalete: {{ $equipo->jugadores->where('tipo', 'brazalete')->count() }}</p>
                        <p class="mb-1">Total de Jugadores: {{ $equipo->jugadores->count() }}</p>
                        <div class="text-center mt-3">
                            <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-outline-light m-1">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            @auth
                                @can('Editar Equipos')
                                    <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary m-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endcan
                                @can('Borrar Equipos')
                                    <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger m-1 delete-equipo" data-id="{{ $equipo->id }}">
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
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

