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
    @can('Crear Jugadores')
    <a href="{{ route('jugador.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Jugador</a>
    @endcan
    <div class="table-responsive">
        <table id="jugadoresTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th class="cedula-column">Cédula</th> {{-- Siempre presente en el DOM --}}
                    <th>Edad</th>
                    <th>Tipo</th>
                    <th>Equipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jugadores as $jugador)
                    <tr>
                        <td>{{ $jugador->dorsal }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($jugador->foto)
                                    <img src="{{ asset($jugador->foto) }}" alt="{{ $jugador->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px; border-radius: 50%;">
                                @endif
                                {{ $jugador->nombre }}
                            </div>
                        </td>
                        <td class="cedula-column">{{ $jugador->cedula }} {{-- Siempre presente en el DOM --}}</td>
                        <td>{{ $jugador->edad }}</td>
                        <td>{{ ucfirst($jugador->tipo) }}</td>
                        <td>{{ $jugador->equipo->nombre }}</td>
                        <td><a href="{{ route('jugador.show', $jugador) }}" class="btn btn-sm btn-outline-light">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            @can('Editar Jugadores')
                            <a href="{{ route('jugador.edit', $jugador) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @endcan
                            @can('Borrar Jugadores')
                            <form action="{{ route('jugador.destroy', $jugador) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger delete-jugador" data-id="{{ $jugador->id }}">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                            @endcan
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
    $(document).ready(function() {
        const table = $('#jugadoresTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "responsive": true,
            "order": [[1, "asc"]], // Ordenar por la columna Nombre (índice 2)
            "columnDefs": [
                { "orderable": false, "targets": [6] } // Deshabilitar orden en Foto y Acciones
            ]
        });

        // Ocultar la columna "Cédula" si el usuario no tiene permiso
        @cannot('Ver Cedula')
            table.column('.cedula-column').visible(false);
        @endcannot

        // Confirmación para eliminar jugador
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
    });
</script>
@endpush

