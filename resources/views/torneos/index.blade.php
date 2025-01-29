@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Torneos</h1>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <a href="{{ route('torneos.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Torneo</a>
    
    <div class="table-responsive">
        <table id="torneosTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Fecha Inicio</th>
                    <th>Estado</th>
                    <th>Equipos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($torneos as $torneo)
                    <tr>
                        <td>{{ $torneo->nombre }}</td>
                        <td>{{ ucfirst($torneo->tipo) }}</td>
                        <td>{{ $torneo->fecha_inicio instanceof \Carbon\Carbon ? $torneo->fecha_inicio->format('d/m/Y') : $torneo->fecha_inicio }}</td>
                        <td>{{ ucfirst($torneo->estado) }}</td>
                        <td>Num de equipos</td>
                        <td>
                            <a href="{{ route('torneos.show', $torneo) }}" class="btn btn-outline-light m-1">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-outline-primary m-1">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('torneos.destroy', $torneo) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger delete-torneo m-1" data-id="{{ $torneo->id }}">
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
        $('#torneosTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "responsive": true
        });

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

