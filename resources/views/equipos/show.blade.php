@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">{{ $equipo->nombre }}</h1>
    
    <div class="card">
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
                    <p class="text-center mb-1">Jugadores Habilidosos:</p>
                    <p class="text-center mb-1">Jugadores con Brazalete:</p>
                    <p class="text-center mb-1">Total de Jugadores:</p>
                </div>
                
                <!-- Right side: Players table -->
                <div class="col-md-8">
                    <h5>Jugadores</h5>
                    <table id="playersTable" class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>F/N</th>
                                <th>Edad</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Jugador 1</td>
                                <td>001</td>
                                <td>01/01/0001</td>
                                <th>11</th>
                                <td>Habilidoso</td>
                                <td>Acciones</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jugador 2</td>
                                <td>002</td>
                                <td>02/02/0002</td>
                                <th>22</th>
                                <td>Habilidoso</td>
                                <td>Acciones</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Jugador 3</td>
                                <td>003</td>
                                <td>03/03/0003</td>
                                <th>33</th>
                                <td>Habilidoso</td>
                                <td>Acciones</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary m-1">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>    
        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary m-1">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#playersTable').DataTable({
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
            "pageLength": -1
        });
    });
</script>
@endpush

