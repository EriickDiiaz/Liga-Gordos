@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Detalles del Partido</h1>
    
    <div class="card mb-4">
        <div class="card-body text-center">
            <h1 class="card-title">{{ $partido->torneo->nombre }}</h1>
            <h5 class="card-subtitle mb-2 text-muted">{{ $partido->fase }} - Grupo {{ $partido->grupo->nombre ?? 'Sin grupo' }}</h5>
            

            <div class="d-flex my-3">
                <div class="col-4">
                    <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 200px;">
                    <h4 class="mt-2">{{ $partido->equipoLocal->nombre }}</h4>
                    <h2>{{ $partido->goles_local ?? 0 }}</h2>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <h1 class="mx-2">VS</h1>
                </div>
                <div class="col-4">
                    <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 200px;">
                    <h4 class="mt-2">{{ $partido->equipoVisitante->nombre }}</h4>
                    <h2>{{ $partido->goles_visitante ?? 0 }}</h2>
                </div>
            </div> 
            <span class="badge bg-{{ $partido->estado == 'programado' ? 'primary' : ($partido->estado == 'en_curso' ? 'success' : 'secondary') }}">
                {{ ucfirst($partido->estado) }}
            </span>
            <h5 class="mt-3">{{ $partido->fecha->format('d/m/Y h:i A') }}</h5>
            <p><strong>Tipo:</strong> {{ ucfirst($partido->tipo) }}</p>
        </div>
    </div>

    <h2 class="text-center">Acciones del Partido</h2>
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
                            <i class="fas fa-futbol fa-2x text-light"></i>
                        @elseif($accion->tipo_accion == 'tarjeta_amarilla')
                            <i class="fa-solid fa-mobile-button fa-2x text-warning"></i>
                        @elseif($accion->tipo_accion == 'tarjeta_roja')
                            <i class="fa-solid fa-mobile-button fa-2x text-danger"></i>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-accion" data-accion-id="{{ $accion->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="text-center">Registrar Nueva Acción</h3>
    <div class="row justify-content-center">
        <div class="col-md-6">
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
                <div class="text-center">
                    <button type="submit" class="btn btn-outline-primary">Registrar Acción</button>
                </div>                
            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('partidos.index') }}" class="btn btn-outline-secondary m-1">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary m-1">Editar Partido</a>
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

