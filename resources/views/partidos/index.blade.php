@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Partidos</h1>
        @can('Crear Partidos')
        <a href="{{ route('partidos.create') }}" class="btn btn-outline-success">
            <i class="fas fa-plus"></i> Crear Nuevo Partido
        </a>
        @endcan
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $totalPartidos }}</h3>
                    <p class="mb-0">Total Partidos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $partidosHoy }}</h3>
                    <p class="mb-0">Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $proximosPartidos }}</h3>
                    <p class="mb-0">Próximos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary text-white">
                <div class="card-body text-center">
                    <h3>{{ $partidosFinalizados }}</h3>
                    <p class="mb-0">Finalizados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter"></i> Filtros
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('partidos.index') }}" id="filtro-form">
                <div class="row">
                    <!-- Filtros rápidos -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Vista rápida:</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="filtro" id="destacados" value="destacados" {{ $filtro == 'destacados' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="destacados">
                                <i class="fas fa-star"></i> Destacados
                            </label>

                            <input type="radio" class="btn-check" name="filtro" id="proximos" value="proximos" {{ $filtro == 'proximos' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="proximos">
                                <i class="fas fa-calendar-plus"></i> Próximos
                            </label>

                            <input type="radio" class="btn-check" name="filtro" id="recientes" value="recientes" {{ $filtro == 'recientes' ? 'checked' : '' }}>
                            <label class="btn btn-outline-info" for="recientes">
                                <i class="fas fa-history"></i> Recientes
                            </label>

                            <input type="radio" class="btn-check" name="filtro" id="todos" value="todos" {{ $filtro == 'todos' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="todos">
                                <i class="fas fa-list"></i> Todos
                            </label>

                            <input type="radio" class="btn-check" name="filtro" id="fecha" value="fecha" {{ $filtro == 'fecha' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning" for="fecha">
                                <i class="fas fa-calendar"></i> Por Fecha
                            </label>
                        </div>
                    </div>

                    <!-- Filtros por fecha -->
                    <div class="col-md-12" id="filtros-fecha" style="{{ $filtro == 'fecha' ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="fecha_especifica" class="form-label">Fecha específica:</label>
                                <input type="date" class="form-control" name="fecha_especifica" id="fecha_especifica" value="{{ $fechaEspecifica }}">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_inicio" class="form-label">Desde:</label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio }}">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_fin" class="form-label">Hasta:</label>
                                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="{{ $fechaFin }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="{{ route('partidos.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Barra de búsqueda -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="search-input" class="form-control" placeholder="Buscar partidos...">
            </div>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge bg-light text-dark fs-6">
                Mostrando {{ $partidos->count() }} partidos
                @if($filtro == 'destacados')
                    (Próximos y Recientes)
                @elseif($filtro == 'proximos')
                    (Próximos)
                @elseif($filtro == 'recientes')
                    (Recientes)
                @elseif($filtro == 'fecha')
                    @if($fechaEspecifica)
                        ({{ \Carbon\Carbon::parse($fechaEspecifica)->format('d/m/Y') }})
                    @elseif($fechaInicio && $fechaFin)
                        ({{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }})
                    @endif
                @endif
            </span>
        </div>
    </div>

    <!-- Contenedor de partidos -->
    <div id="partidos-container" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($partidos as $partido)
            <div class="col partido-item">
                <div class="card h-100 text-center {{ $partido->fecha->isFuture() ? 'border-success' : ($partido->fecha->isToday() ? 'border-warning' : 'border-secondary') }}">
                    <!-- Indicador de tiempo -->
                    <div class="card-header p-1">
                        @if($partido->fecha->isToday())
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock"></i> HOY
                            </span>
                        @elseif($partido->fecha->isFuture())
                            <span class="badge bg-success">
                                <i class="fas fa-calendar-plus"></i> 
                                {{ $partido->fecha->diffForHumans() }}
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-history"></i> 
                                {{ $partido->fecha->diffForHumans() }}
                            </span>
                        @endif
                    </div>

                    <div class="card-body p-2">
                        @if($partido->esAmistoso())
                            <h5 class="card-title">Partido Amistoso</h5>
                            <p class="card-subtitle mb-2 text-muted small">{{ $partido->descripcion }}</p>
                        @else
                            <h5 class="card-title">{{ $partido->torneo->nombre }}</h5>
                            <p class="card-subtitle mb-2 text-muted small">
                                @if($partido->esLiga())
                                    {{ $partido->fase }} - {{ $partido->grupo->nombre ?? 'Sin grupo' }}
                                @elseif($partido->esEliminatoria())
                                    {{ $partido->fase }} - {{ $partido->esIda() ? 'IDA' : 'VUELTA' }}
                                @endif
                            </p>
                        @endif
                        
                        <div class="d-flex my-2">
                            <div class="col-4">
                                <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid" style="max-height: 60px;">
                                <p class="mt-1 mb-0 small">{{ $partido->equipoLocal->nombre }}</p>
                                <h4>{{ $partido->goles_local ?? 0 }}</h4>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <h5 class="mx-2">VS</h5>
                            </div>
                            <div class="col-4">
                                <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid" style="max-height: 60px;">
                                <p class="mt-1 mb-0 small">{{ $partido->equipoVisitante->nombre }}</p>
                                <h4>{{ $partido->goles_visitante ?? 0 }}</h4>
                            </div>
                        </div>
                        
                        <span class="badge bg-{{ $partido->estado == 'programado' ? 'primary' : ($partido->estado == 'en_curso' ? 'success' : 'secondary') }}">
                            {{ ucfirst($partido->estado) }}
                        </span>
                        <p class="mt-2 mb-0 small">{{ $partido->fecha->format('d/m/Y h:i A') }}</p>
                    </div>
                    
                    <div class="card-footer p-2">
                        <a href="{{ route('partidos.show', $partido) }}" class="btn btn-outline-light btn-sm m-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('Editar Partidos')
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-outline-primary btn-sm m-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('Borrar Partidos')
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm m-1 delete-partido" data-id="{{ $partido->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        @endcan
                        <button class="btn btn-sm btn-outline-light m-1" onclick="generarImagenPartido({{ $partido->id }})">
                            <i class="fa-solid fa-share-nodes"></i>
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
                                    <span class="fs-5">Cancha Techada</span>
                                </div>
                                <div class="text-center mt-4">
                                    <span class="small" style="color:#bbb;">Patrocinan:</span><br>
                                    @foreach ($patrocinadores as $patrocinador)
                                        <img src="{{ asset($patrocinador->logo) }}" alt="{{ $patrocinador->nombre }}" style="height:40px; margin: 0 5px;">
                                        
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <a id="descargarImagenBtn" class="btn btn-primary" download="partido.png">Descargar</a>
                                </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>No se encontraron partidos</h4>
                    <p>No hay partidos que coincidan con los filtros seleccionados.</p>
                    @if($filtro == 'fecha')
                        <a href="{{ route('partidos.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Ver todos los partidos
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Mensaje de No Resultados de búsqueda -->
    <div id="no-results" class="alert alert-info mt-3 d-none">
        <i class="fas fa-search"></i> No se encontraron partidos que coincidan con la búsqueda.
    </div>

    
</div>

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
        // Funcionalidad de búsqueda
        const searchInput = document.getElementById('search-input');
        const partidosContainer = document.getElementById('partidos-container');
        const partidoItems = document.querySelectorAll('.partido-item');
        const noResults = document.getElementById('no-results');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let hasResults = false;
            
            partidoItems.forEach(item => {
                const cardContent = item.textContent.toLowerCase();
                
                if (cardContent.includes(searchTerm)) {
                    item.classList.remove('d-none');
                    hasResults = true;
                } else {
                    item.classList.add('d-none');
                }
            });
            
            // Mostrar mensaje si no hay resultados
            if (hasResults || searchTerm === '') {
                noResults.classList.add('d-none');
            } else {
                noResults.classList.remove('d-none');
            }
        });
        
        // Confirmación de eliminación
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

        // Manejo de filtros
        const filtroRadios = document.querySelectorAll('input[name="filtro"]');
        const filtrosFecha = document.getElementById('filtros-fecha');
        
        filtroRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'fecha') {
                    filtrosFecha.style.display = 'block';
                } else {
                    filtrosFecha.style.display = 'none';
                    // Auto-submit para filtros rápidos
                    document.getElementById('filtro-form').submit();
                }
            });
        });

        // Limpiar fechas cuando se cambia de filtro por fecha
        const fechaInputs = document.querySelectorAll('#fecha_especifica, #fecha_inicio, #fecha_fin');
        fechaInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Si se selecciona fecha específica, limpiar rango
                if (this.id === 'fecha_especifica' && this.value) {
                    document.getElementById('fecha_inicio').value = '';
                    document.getElementById('fecha_fin').value = '';
                }
                // Si se selecciona rango, limpiar fecha específica
                if ((this.id === 'fecha_inicio' || this.id === 'fecha_fin') && this.value) {
                    document.getElementById('fecha_especifica').value = '';
                }
            });
        });


    });
</script>
@endpush

@endsection
