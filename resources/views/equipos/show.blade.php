@extends('layouts.app')
@section('title', $equipo->nombre)
@section('content')

<!-- Mensajes y alertas -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container">

    <!-- Titulo -->
    <div>
        <h1 class="mb-3 text-center"><i class="fa-solid fa-shield me-2"></i>{{ $equipo->nombre }}</h1>
    </div>
    
    <div class="card">
        <div class="card-header" style="border-color: {{ $equipo->color_primario }}; background: {{ $equipo->color_primario }};"></div>
        <div class="card-body">
            <div class="row">
                <!-- Left side: Team information -->
                <div class="col-md-4">
                    <h5>Información del Equipo</h5>
                    <div class="text-center mb-4">
                        <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid" style="max-height: 200px;">
                    </div>
                    <p class="card-text text-center mb-1">
                        Estado: <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                            {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                    <p class="card-text text-center mb-1">
                        Color Primario: <span style="color: {{ $equipo->color_primario }};">■</span>
                        @if($equipo->color_secundario)
                            Color Secundario: <span style="color: {{ $equipo->color_secundario }};">■</span>
                        @endif
                    </p>                    
                    <p class="text-center mb-1">Jugadores Habilidosos: {{ $equipo->jugadores->where('tipo', 'habilidoso')->count() }}</p>
                    <p class="text-center mb-1">Jugadores con Brazalete: {{ $equipo->jugadores->where('tipo', 'brazalete')->count() }}</p>
                    <p class="text-center mb-1">Total de Jugadores: {{ $equipo->jugadores->count() }}</p>
                    <p class="card-text text-center mb-1">
                        Estado: <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                            {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                    <div class="d-flex justify-content-center align-items-center">
                        @if($equipo->instagram)
                            <a href="{{ $equipo->instagram }}" target="blank" class="mx-2">
                                <i class="fa-brands fa-instagram" style="font-size: 1.5rem;"></i>
                            </a>
                        @endif
                        @if($equipo->tiktok)
                            <a href="{{ $equipo->tiktok }}" target="blank" class="mx-2">
                                <i class="fa-brands fa-tiktok" style="font-size: 1.5rem;"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Right side: Players table -->
                <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Jugadores</h5>
                        @can('Crear Jugadores')
                        <a href="{{ route('jugador.create', ['equipo_id' => $equipo->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Agregar Jugador
                        </a>
                        @endcan
                    </div>
                    <table id="playersTable" class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th class="cedula-column">Cédula</th> {{-- Siempre presente en el DOM --}}
                                <th>F/N</th>
                                <th>Edad</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipo->jugadores as $jugador)
                            <tr>
                                <td>{{ $jugador->dorsal }}</td>
                                <td>{{ $jugador->nombre }}</td>
                                <td class="cedula-column">{{ $jugador->cedula }}</td> {{-- Siempre presente en el DOM --}}
                                <td>{{ $jugador->fecha_nacimiento->format('d/m/Y') }}</td>
                                <td>{{ $jugador->edad }}</td>
                                <td>{{ ucfirst($jugador->tipo) }}</td>
                                <td>
                                    <a href="{{ route('jugador.show', $jugador) }}" class="btn btn-outline-light btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('Editar Jugadores')
                                    <a href="{{ route('jugador.edit', $jugador) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('Borrar Jugadores')
                                    <form action="{{ route('jugador.destroy', $jugador) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm delete-jugador" data-id="{{ $jugador->id }}">
                                            <i class="fas fa-trash-alt"></i>
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
        </div>
        <div class="card-footer" style="border-color: {{ $equipo->color_secundario }}; background: {{ $equipo->color_secundario }};"></div>
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
        @can('Editar Equipos') 
        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
        @endcan
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const table = $('#playersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "info": "",
                "infoEmpty": "",
                "infoFiltered": ""
            },
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true,
            "lengthChange": false,
            "pageLength": -1,
            "responsive": true
        });

        // Ocultar la columna "Cédula" si el usuario no está autenticado
        @guest
            table.column('.cedula-column').visible(false);
        @endguest

        // Confirmación para eliminar jugador
        $('.delete-jugador').click(function(e) {
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

