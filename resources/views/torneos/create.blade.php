@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Nuevo Torneo</h1>

    <form action="{{ route('torneos.store') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="card-header">
                <h2>Detalles del Torneo</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Torneo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Torneo</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="eliminatoria">Eliminatoria</option>
                        <option value="liga">Liga</option>
                        <option value="mixto">Mixto</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="planificado">Planificado</option>
                        <option value="en_curso">En Curso</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 mb-4">
            <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary m-1">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
            @can('crear torneos') 
            <button type="submit" class="btn btn-outline-success m-1">Crear Torneo</button>
            @endcan
        </div>
    </form>

    <div class="row mt-4"> 
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Grupos</h2>
                </div>
                <div class="card-body">
                    <div id="grupos-container">
                        <!-- Los grupos se agregarán aquí dinámicamente -->
                    </div>
                    @can('crear torneos')
                    <button type="button" class="btn btn-outline-primary" id="agregar-grupo">Agregar Grupo</button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>Agregar Equipo a Grupo</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="equipo_id" class="form-label">Equipo</label>
                        <select id="equipo_id" class="form-select" required>
                            @foreach(App\Models\Equipo::all() as $equipo)
                                <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grupo_id" class="form-label">Grupo</label>
                        <select id="grupo_id" class="form-select" required>
                            <!-- Las opciones se agregarán dinámicamente -->
                        </select>
                    </div>
                    @can('crear torneos')
                    <button type="button" class="btn btn-outline-primary" id="agregar-equipo">Agregar Equipo al Grupo</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let grupoCount = 0;

        $('#agregar-grupo').click(function() {
            grupoCount++;
            const grupoHtml = `
                <div class="card mb-3 grupo-card" id="grupo-${grupoCount}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control w-75" name="grupos[${grupoCount}][nombre]" placeholder="Nombre del Grupo" required>
                        <button type="button" class="btn btn-outline-danger btn-sm eliminar-grupo" data-grupo-id="${grupoCount}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <ul class="list-group equipos-list" id="equipos-grupo-${grupoCount}"></ul>
                    </div>
                </div>
            `;
            $('#grupos-container').append(grupoHtml);
            
            // Agregar opción al select de grupos
            $('#grupo_id').append(`<option value="${grupoCount}">${grupoCount}</option>`);
        });

        // Eliminar grupo
        $(document).on('click', '.eliminar-grupo', function() {
            const grupoId = $(this).data('grupo-id');
            $(`#grupo-${grupoId}`).remove();
            $(`#grupo_id option[value="${grupoId}"]`).remove();
        });

        // Agregar equipo a grupo
        $('#agregar-equipo').click(function() {
            const equipoId = $('#equipo_id').val();
            const equipoNombre = $('#equipo_id option:selected').text();
            const grupoId = $('#grupo_id').val();
            
            if (equipoId && grupoId) {
                const equipoHtml = `
                    <li class="list-group-item d-flex justify-content-between align-items-center" id="equipo-${equipoId}">
                        <div>
                            <img src="{{ asset('img/default-team.png') }}" alt="${equipoNombre}" class="img-fluid me-2" style="max-height: 30px; max-width: 30px;">
                            ${equipoNombre}
                        </div>
                        <input type="hidden" name="grupos[${grupoId}][equipos][]" value="${equipoId}">
                        <button type="button" class="btn btn-outline-danger btn-sm eliminar-equipo" data-equipo-id="${equipoId}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </li>
                `;
                $(`#equipos-grupo-${grupoId}`).append(equipoHtml);
            }
        });

        // Eliminar equipo de grupo
        $(document).on('click', '.eliminar-equipo', function() {
            $(this).closest('li').remove();
        });
    });
</script>
@endpush

