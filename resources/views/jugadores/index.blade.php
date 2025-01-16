@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1 class="mb-4">Jugadores</h1>
    
    
    
    <a href="{{ route('jugadores.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Jugador</a>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Edad</th>
                    <th>Dorsal</th>
                    <th>Tipo</th>
                    <th>Equipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jugadores as $jugador)
                    <tr>
                        <td>
                            @if($jugador->foto)
                                <img class="img-thumbnail" src="{{ asset('storage/' . $jugador->foto) }}" alt="{{ $jugador->nombre }}" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <img class="img-thumbnail" src="{{ asset('img/default-player.png') }}" alt="Default" style="width: 50px; height: 50px; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $jugador->nombre }}</td>
                        <td>{{ $jugador->cedula }}</td>
                        <td>{{ $jugador->edad }}</td>
                        <td>{{ $jugador->dorsal }}</td>
                        <td>{{ ucfirst($jugador->tipo) }}</td>
                        <td>{{ $jugador->equipo->nombre }}</td>
                        <td>
                            <a href="{{ route('jugadores.show', $jugador) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('jugadores.edit', $jugador) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('jugadores.destroy', $jugador) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm delete-jugador" data-id="{{ $jugador->id }}">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-jugador');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const jugadorId = this.getAttribute('data-id');
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

