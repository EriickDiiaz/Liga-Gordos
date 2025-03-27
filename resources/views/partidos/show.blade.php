@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalles del Partido</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h2>
                @if($partido->esAmistoso())
                    Partido Amistoso
                @elseif($partido->esLiga())
                    Partido de Liga
                @elseif($partido->esEliminatoria())
                    Partido de Eliminatoria - {{ $partido->esIda() ? 'IDA' : 'VUELTA' }}
                @endif
            </h2>
        </div>
        <div class="card-body text-center">
            @if(!$partido->esAmistoso())
                <h3 class="card-title">{{ $partido->torneo->nombre }}</h3>
                <h5 class="card-subtitle mb-2 text-muted">
                    @if($partido->esLiga())
                        {{ $partido->grupo->nombre ?? 'Sin grupo' }}
                    @elseif($partido->esEliminatoria())
                        {{ $partido->fase }}
                    @endif
                </h5>
            @else
                <h5 class="card-subtitle mb-2 text-muted">{{ $partido->descripcion }}</h5>
            @endif
            <div class="d-flex justify-content-between align-items-center my-3">
                <div class="text-center">
                    <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 100px;">
                    <h4 class="mt-2">{{ $partido->equipoLocal->nombre }}</h4>
                    <h2>{{ $partido->goles_local ?? 0 }}</h2>
                </div>
                <h1 class="mx-2">VS</h1>
                <div class="text-center">
                    <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 100px;">
                    <h4 class="mt-2">{{ $partido->equipoVisitante->nombre }}</h4>
                    <h2>{{ $partido->goles_visitante ?? 0 }}</h2>
                </div>
            </div>
            <span class="badge bg-{{ $partido->estado == 'programado' ? 'primary' : ($partido->estado == 'en_curso' ? 'success' : 'secondary') }}">
                {{ ucfirst($partido->estado) }}
            </span>
            <h5 class="mt-3">{{ $partido->fecha->format('d/m/Y h:i A') }}</h5>
            
            @if($partido->esEliminatoria() && $partido->partidoRelacionado)
                <div class="mt-4 p-3 bg-light rounded">
                    <h4>Resultado Global</h4>
                    @php
                        $resultadoGlobal = $partido->resultadoGlobal();
                    @endphp
                    @if($resultadoGlobal)
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="text-center">
                                <h5>{{ $partido->esIda() ? $partido->equipoLocal->nombre : $partido->equipoVisitante->nombre }}</h5>
                                <h3>{{ $resultadoGlobal['local'] }}</h3>
                            </div>
                            <h4 class="mx-3">-</h4>
                            <div class="text-center">
                                <h5>{{ $partido->esIda() ? $partido->equipoVisitante->nombre : $partido->equipoLocal->nombre }}</h5>
                                <h3>{{ $resultadoGlobal['visitante'] }}</h3>
                            </div>
                        </div>
                        @php
                            $ganador = $partido->ganadorEliminatoria();
                        @endphp
                        @if($ganador)
                            <div class="mt-2">
                                <h5>Equipo clasificado: <strong>{{ $ganador->nombre }}</strong></h5>
                            </div>
                        @endif
                    @else
                        <p>No se puede calcular el resultado global todavía.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($partido->esEliminatoria() && $partido->esIda() && $partido->partidosVuelta->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3>Partido de Vuelta</h3>
            </div>
            <div class="card-body">
                @foreach($partido->partidosVuelta as $partidoVuelta)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>{{ $partidoVuelta->equipoLocal->nombre }} vs {{ $partidoVuelta->equipoVisitante->nombre }}</h5>
                            <p>{{ $partidoVuelta->fecha->format('d/m/Y h:i A') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('partidos.show', $partidoVuelta) }}" class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($partido->esEliminatoria() && !$partido->esIda() && $partido->partidoRelacionado)
        <div class="card mb-4">
            <div class="card-header">
                <h3>Partido de Ida</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>{{ $partido->partidoRelacionado->equipoLocal->nombre }} vs {{ $partido->partidoRelacionado->equipoVisitante->nombre }}</h5>
                        <p>{{ $partido->partidoRelacionado->fecha->format('d/m/Y h:i A') }}</p>
                        <h4>{{ $partido->partidoRelacionado->goles_local ?? 0 }} - {{ $partido->partidoRelacionado->goles_visitante ?? 0 }}</h4>
                    </div>
                    <div>
                        <a href="{{ route('partidos.show', $partido->partidoRelacionado) }}" class="btn btn-primary">Ver Detalles</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <h2>Acciones del Partido</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Jugador</th>
                <th>Equipo</th>
                <th>Acción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partido->acciones as $accion)
                <tr>
                    <td>{{ $accion->jugador->nombre }}</td>
                    <td>{{ $accion->jugador->equipo->nombre }}</td>
                    <td>
                        @if($accion->tipo_accion == 'gol')
                            <i class="fas fa-futbol fa-2x text-success"></i>
                        @elseif($accion->tipo_accion == 'tarjeta_amarilla')
                            <i class="fas fa-square fa-2x text-warning"></i>
                        @elseif($accion->tipo_accion == 'tarjeta_roja')
                            <i class="fas fa-square fa-2x text-danger"></i>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm delete-accion" data-accion-id="{{ $accion->id }}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Registrar Nueva Acción</h3>
    <form action="{{ route('partidos.registrar-accion', $partido) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="jugador_id" class="form-label">Jugador</label>
            <select name="jugador_id" id="jugador_id" class="form-control" required>
                <option value="">Seleccione un jugador</option>
                @foreach($partido->equipoLocal->jugadores as $jugador)
                    <option value="{{ $jugador->id }}">{{ $jugador->nombre }} ({{ $partido->equipoLocal->nombre }})</option>
                @endforeach
                @foreach($partido->equipoVisitante->jugadores as $jugador)
                    <option value="{{ $jugador->id }}">{{ $jugador->nombre }} ({{ $partido->equipoVisitante->nombre }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tipo_accion" class="form-label">Tipo de Acción</label>
            <select name="tipo_accion" id="tipo_accion" class="form-control" required>
                <option value="gol">Gol</option>
                <option value="tarjeta_amarilla">Tarjeta Amarilla</option>
                <option value="tarjeta_roja">Tarjeta Roja</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Acción</button>
    </form>

    <div class="mt-4">
        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-primary">Editar Partido</a>
        <a href="{{ route('partidos.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-accion');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const accionId = this.getAttribute('data-accion-id');
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
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ route('partidos.eliminar-accion', ['partido' => $partido->id, 'accion' => ':accion']) }}`.replace(':accion', accionId);
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

