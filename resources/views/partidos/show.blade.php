@extends('layouts.app')

@push('styles')
<style>
    #contenedorImagenVistaPrevia canvas {
        max-width: 100%;
        height: auto !important;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Detalles del Partido</h1>
    
    <div class="card mb-4">
        <div class="card-body text-center">
            @if($partido->esAmistoso())
                <h1 class="card-title">Partido Amistoso</h1>
                <h5 class="card-subtitle mb-2 text-muted">{{ $partido->descripcion }}</h5>
            @else
                <h1 class="card-title">{{ $partido->torneo->nombre }}</h1>
                @if($partido->esLiga())
                    <h5 class="card-subtitle mb-2 text-muted">{{ $partido->fase }} - {{ $partido->grupo->nombre ?? 'Sin grupo' }}</h5>
                @elseif($partido->esEliminatoria())
                    <h5 class="card-subtitle mb-2 text-muted">{{ $partido->fase }} - {{ $partido->esIda() ? 'IDA' : 'VUELTA' }}</h5>
                @endif
            @endif

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
            <div class="mt-3">
                @can('Registrar Acciones')
                    @if($partido->estado == 'programado')
                        <form action="{{ route('partidos.iniciar', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success m-1">
                                <i class="fas fa-play"></i> Iniciar Partido
                            </button>
                        </form>
                    @endif
                    
                    @if($partido->estado == 'en_curso')
                        <form action="{{ route('partidos.finalizar', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger m-1">
                                <i class="fas fa-flag-checkered"></i> Finalizar Partido
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
            <h5 class="mt-3"><i class="fa-solid fa-calendar-days me-2"></i>{{ $partido->fecha->format('d/m/Y h:i A') }}</h5>
            <h5><i class="fa-solid fa-location-dot me-2"></i>Cancha Techada de Charallave</h5>
            <p><strong>Tipo:</strong> {{ ucfirst($partido->tipo) }}</p>

            <button class="btn btn-outline-light m-1" onclick="generarImagenPartido({{ $partido->id }})">
                <i class="fa-solid fa-share-nodes me-2"></i>Compartir
            </button>

            <!-- DIV Oculto con la informacion que se mostrará en la imagen -->
            <div id="partido-share-{{ $partido->id }}" style="visibility:hidden;position:fixed;left:-9999px;top:0;">
                <div class="mx-auto p-4 bg-dark text-white rounded-4 shadow-lg" style="width:600px; font-family: 'Segoe UI', Arial, sans-serif;">
                    <div class="text-center mb-4">
                        <img src="{{ asset('img/liga-gordos-logo.png') }}" alt="Liga Logo" style="height:100px;">
                    </div>
                    <h3 class="text-center mb-2">
                        @if($partido->esAmistoso())
                            Partido Amistoso
                        @else
                            {{ $partido->torneo->nombre }}
                        @endif
                    </h3>
                    <p class="text-center mb-4">
                        @if($partido->esLiga())
                            {{ $partido->fase }} - {{ $partido->grupo->nombre ?? 'Sin grupo' }}
                        @elseif($partido->esEliminatoria())
                            {{ $partido->fase }} - {{ $partido->esIda() ? 'IDA' : 'VUELTA' }}
                        @endif
                    </p>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="flex-fill text-center">
                            <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" style="max-height:90px;">
                            <div class="fw-bold mt-2">{{ $partido->equipoLocal->nombre }}</div>
                            <div class="fs-1 mt-2">{{ $partido->goles_local ?? 0 }}</div>
                        </div>
                        <div class="flex-shrink-0 text-center" style="width:80px;">
                            <span class="fs-2 fw-bold">VS</span>
                        </div>
                        <div class="flex-fill text-center">
                            <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" style="max-height:90px;">
                            <div class="fw-bold mt-2">{{ $partido->equipoVisitante->nombre }}</div>
                            <div class="fs-1 mt-2">{{ $partido->goles_visitante ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <span class="badge bg-{{ $partido->estado == 'programado' ? 'primary' : ($partido->estado == 'en_curso' ? 'success' : 'secondary') }}">
                            {{ ucfirst($partido->estado) }}
                        </span>
                    </div>
                    <div class="text-center">
                        <i class="fa-solid fa-calendar-days me-2 fa-lg"></i>
                        <span class="fs-5">{{ $partido->fecha->format('d/m/Y h:i A') }}</span>
                    </div>
                    <div class="text-center">
                        <i class="fa-solid fa-location-dot me-2 fa-lg"></i>
                        <span class="fs-5">Cancha Techada de Charallave</span>
                    </div>
                    <div class="text-center mt-4">
                        <span class="small" style="color:#bbb;">Patrocinan:</span><br>
                        @foreach ($patrocinadores as $patrocinador)
                            <img src="{{ asset($patrocinador->logo) }}" alt="{{ $patrocinador->nombre }}" style="height:60px; margin: 0 5px;">     
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Modal de vista previa de imagen -->
            <div class="modal fade" id="modalVistaPrevia" tabindex="-1" aria-labelledby="modalVistaPreviaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content bg-dark text-white">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalVistaPreviaLabel">Vista previa de la imagen</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="contenedorImagenVistaPrevia"></div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-delete-left"></i> Cerrar
                        </button>
                        <a id="descargarImagenBtn" class="btn btn-outline-primary" download="partido.png">
                            <i class="fa-solid fa-download"></i> Descargar
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            
            @if($partido->esEliminatoria() && $partido->partidoRelacionado)
                <div class="mt-4 p-3 border border-warning-subtle rounded">
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
            
            @if($partido->esEliminatoria() && $partido->esIda() && $partido->partidosVuelta->count() > 0)
                <div class="mt-4">
                    <h4>Partido de Vuelta</h4>
                    @foreach($partido->partidosVuelta as $partidoVuelta)
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <div>
                                <p>{{ $partidoVuelta->equipoLocal->nombre }} vs {{ $partidoVuelta->equipoVisitante->nombre }}</p>
                                <p>{{ $partidoVuelta->fecha->format('d/m/Y h:i A') }}</p>
                                <a href="{{ route('partidos.show', $partidoVuelta) }}" class="btn btn-outline-primary btn-sm">Ver Detalles</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            @if($partido->esEliminatoria() && !$partido->esIda() && $partido->partidoRelacionado)
                <div class="mt-4">
                    <h4>Partido de Ida</h4>
                    <div class="d-flex justify-content-center align-items-center mt-2">
                        <div>
                            <p>{{ $partido->partidoRelacionado->equipoLocal->nombre }} vs {{ $partido->partidoRelacionado->equipoVisitante->nombre }}</p>
                            <p>{{ $partido->partidoRelacionado->fecha->format('d/m/Y h:i A') }}</p>
                            <p>Resultado: {{ $partido->partidoRelacionado->goles_local ?? 0 }} - {{ $partido->partidoRelacionado->goles_visitante ?? 0 }}</p>
                            <a href="{{ route('partidos.show', $partido->partidoRelacionado) }}" class="btn btn-outline-primary btn-sm">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            @endif
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
    @can('Registrar Acciones')
    @if($partido->estado == 'en_curso')
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
    @endif
    @endcan

    <div class="text-center mt-4">
        <a href="{{ route('partidos.index') }}" class="btn btn-outline-secondary m-1">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
        @can('Editar Partidos')
        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary m-1">Editar Partido</a>
        @endcan
    </div>
</div>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    // Script para generar y descargar/compartir la imagen
    function generarImagenPartido(partidoId) {
        const elemento = document.getElementById('partido-share-' + partidoId);

        // Guardar estilos originales
        const originalStyle = elemento.getAttribute('style') || '';

        // Hacer visible fuera de pantalla
        elemento.setAttribute('style', originalStyle + ';visibility:visible;position:fixed;left:-9999px;top:0;display:block;z-index:9999;');

        html2canvas(elemento, {
            backgroundColor: null,
            scale: 2,
            useCORS: true
        }).then(canvas => {
            // Restaurar estilos originales
            elemento.setAttribute('style', originalStyle);

            // Mostrar la imagen en el modal
            const contenedor = document.getElementById('contenedorImagenVistaPrevia');
            contenedor.innerHTML = '';
            contenedor.appendChild(canvas);

            // Configurar el botón de descarga
            const link = document.getElementById('descargarImagenBtn');
            link.href = canvas.toDataURL('image/png');
            link.download = 'partido_' + partidoId + '.png';

            // Mostrar el modal (Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('modalVistaPrevia'));
            modal.show();
        });
    }

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

