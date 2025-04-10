@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1 class="mb-4">Noticias</h1>
    @can('Crear Noticias')
    <a href="{{ route('noticias.create') }}" class="btn btn-outline-success mb-3">Crear Nueva Noticia</a>
    @endcan
    <div class="table-responsive">
        <table id="noticiasTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Titulo</th>                     
                    <th>Contenido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($noticias as $noticia)
                    <tr>
                        <td>{{ $noticia->created_at->format('d/m/Y') }}</td>
                        <td>{{ $noticia->titulo }}</td>                       
                        <td>{{ $noticia->contenido }}</td>
                        <td>
                            @can('Editar Noticias')
                            <a href="{{ route('noticias.edit', $noticia) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @endcan
                            @can('Borrar Noticias')
                            <form action="{{ route('noticias.destroy', $noticia) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger delete-noticia" data-id="{{ $noticia->id }}">
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
        $('#noticiasTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "responsive": true,
            "order": [[2, "asc"]], // Order by the Name column (index 2) ascending
            "columnDefs": [
                { "orderable": false, "targets": [3] } // Disable sorting for photo and actions columns
            ]
        });

        $('.delete-noticia').click(function(e) {
            e.preventDefault();
            const noticiaId = $(this).data('id');
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

