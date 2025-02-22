@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Torneos</h1>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @can('Crear Torneos')
    <a href="{{ route('torneos.create') }}" class="btn btn-outline-success mb-3">Crear Nuevo Torneo</a>
    @endcan
    
    @foreach($torneos as $torneo)
        <div class="card mb-5">
            <div class="card-header">
                <h2>{{ $torneo->nombre }}</h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="card-title">Detalles del Torneo</h5>
                        <p><strong>Tipo:</strong> {{ ucfirst($torneo->tipo) }}</p>
                        <p><strong>Fecha de Inicio:</strong> {{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
                        <p><strong>Estado:</strong> {{ ucfirst($torneo->estado) }}</p>
                        <p><strong>Equipos participantes:</strong> {{ $torneo->equipos->count() }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Acciones</h5>
                        @can('Editar Torneos')
                        <a href="{{ route('torneos.edit', $torneo) }}" class="btn btn-outline-primary m-1">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @endcan
                        @can('Borrar Torneos')
                        <form action="{{ route('torneos.destroy', $torneo) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger delete-torneo m-1" data-id="{{ $torneo->id }}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                <h3 class="mb-3">Tablas de Posiciones</h3>
                @foreach($torneo->grupos as $grupo)
                    <h4>{{ $grupo->nombre }}</h4>
                    <table class="table table-striped mb-4">
                        <thead>
                            <tr>
                                <th>Posición</th>
                                <th>Equipo</th>
                                <th>PJ</th>
                                <th>PG</th>
                                <th>PE</th>
                                <th>PP</th>
                                <th>GF</th>
                                <th>GC</th>
                                <th>DG</th>
                                <th>PTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tablasPosiciones[$torneo->id][$grupo->id] as $index => $estadisticas)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ asset($estadisticas['equipo']->logo) }}" alt="{{ $estadisticas['equipo']->nombre }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                                        {{ $estadisticas['equipo']->nombre }}
                                    </td>
                                    <td>{{ $estadisticas['PJ'] }}</td>
                                    <td>{{ $estadisticas['PG'] }}</td>
                                    <td>{{ $estadisticas['PE'] }}</td>
                                    <td>{{ $estadisticas['PP'] }}</td>
                                    <td>{{ $estadisticas['GF'] }}</td>
                                    <td>{{ $estadisticas['GC'] }}</td>
                                    <td>{{ $estadisticas['DG'] }}</td>
                                    <td>{{ $estadisticas['PTS'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

                <h3 class="mb-3">Partidos</h3>
                <table class="table partidosTable">
                    <thead>
                        <tr>
                            <th>Local</th>
                            <th>Visitante</th>
                            <th>Fase</th>
                            <th>Fecha</th>
                            <th>Resultado</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($torneo->partidos as $partido)
                            <tr>
                                <td>
                                    <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                                    {{ $partido->equipoLocal->nombre }}
                                </td>
                                <td>
                                    <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                                    {{ $partido->equipoVisitante->nombre }}
                                </td>
                                <td>{{ $partido->fase }}</td>
                                <td>{{ $partido->fecha->format('d/m/Y h:i A') }}</td>
                                <td>
                                    @if($partido->goles_local !== null && $partido->goles_visitante !== null)
                                        {{ $partido->goles_local }} - {{ $partido->goles_visitante }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ ucfirst($partido->estado) }}</td>
                                <td>
                                    <a href="{{ route('partidos.show', $partido) }}" class="btn btn-outline-light m-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('Editar Partidos')
                                    <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('Borrar Partidos')
                                    <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger m-1 delete-partido" data-id="{{ $partido->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3 class="mb-3 mt-4">Grupos y Equipos</h3>
                <div class="row">
                    @foreach($torneo->grupos as $grupo)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">{{ $grupo->nombre }}</div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($grupo->equipos as $equipo)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px;">
                                                    {{ $equipo->nombre }}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.partidosTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "responsive": true,
            "order": [[3, "desc"]]
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
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-partido');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const partidoId = this.getAttribute('data-id');
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

