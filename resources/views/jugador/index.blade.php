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
    
    <a href="{{ route('jugador.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Jugador</a>
    
    <div class="table-responsive">
        <table id="jugadoresTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
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
                            <img class="img-thumbnail" src="{{ asset($jugador->foto) }}" class="card-img-top mt-3" alt="{{ $jugador->nombre }}" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>{{ $jugador->nombre }}</td>
                        <td>{{ $jugador->cedula }}</td>
                        <td>{{ $jugador->edad }}</td>                        
                        <td>{{ ucfirst($jugador->tipo) }}</td>
                        <td>{{ $jugador->equipo->nombre }}</td>
                        <td>
                            <a href="{{ route('jugador.show', $jugador) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('jugador.edit', $jugador) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('jugador.destroy', $jugador) }}" method="POST" class="d-inline">
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
    $(document).ready(function() {
        $('#jugadoresTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "responsive": true,
            "order": [[2, "asc"]], // Order by the Name column (index 2) ascending
            "columnDefs": [
                { "orderable": false, "targets": [1, 7] } // Disable sorting for photo and actions columns
            ]
        });

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

