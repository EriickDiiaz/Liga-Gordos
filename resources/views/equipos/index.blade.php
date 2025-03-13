@extends('layouts.app')

@section('content')
<div class="container">
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1 class="mb-3">Equipos</h1>
    
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            @can('Crear Equipos')
            <a href="{{ route('equipos.create') }}" class="btn btn-outline-success">
                <i class="fas fa-plus"></i> Crear Nuevo Equipo
            </a>
            @endcan
        </div>
        
        <div class="d-flex align-items-center">
            <div class="input-group me-3">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="search-input" class="form-control" placeholder="Buscar equipos...">
            </div>
            
            <div class="btn-group" role="group">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sort-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Ordenar
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sort-dropdown">
                        <li><a class="dropdown-item sort-option" data-sort="nombre-asc" href="#">Nombre (A-Z)</a></li>
                        <li><a class="dropdown-item sort-option" data-sort="nombre-desc" href="#">Nombre (Z-A)</a></li>
                        <li><a class="dropdown-item sort-option" data-sort="jugadores-desc" href="#">Más jugadores</a></li>
                        <li><a class="dropdown-item sort-option" data-sort="estado-desc" href="#">Estado (Activo primero)</a></li>
                    </ul>
                </div>
                
                <button id="grid-view-btn" class="btn btn-outline-secondary active" title="Vista de cuadrícula">
                    <i class="fas fa-th"></i>
                </button>
                <button id="list-view-btn" class="btn btn-outline-secondary" title="Vista de lista">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Vista de Cuadrícula (Original) -->
    <div id="grid-view" class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($equipos as $equipo)
            <div class="col equipo-item" 
                 data-nombre="{{ $equipo->nombre }}" 
                 data-estado="{{ $equipo->estado ? 'Activo' : 'Inactivo' }}"
                 data-jugadores="{{ $equipo->jugadores->count() }}">
                <div class="card h-100">
                    <img src="{{ asset($equipo->logo) }}" class="card-img-top mt-3" alt="{{ $equipo->nombre }}" style="height: 180px; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $equipo->nombre }}
                            <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                                {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </h5>
                        <p class="card-text mb-1">
                            Color Primario: <span style="color: {{ $equipo->color_primario }};">■</span>
                            @if($equipo->color_secundario)
                                Color Secundario: <span style="color: {{ $equipo->color_secundario }};">■</span>
                            @endif
                        </p>
                        
                        <p class="mb-1">Jugadores Habilidosos: {{ $equipo->jugadores->where('tipo', 'habilidoso')->count() }}</p>
                        <p class="mb-1">Jugadores con Brazalete: {{ $equipo->jugadores->where('tipo', 'brazalete')->count() }}</p>
                        <p class="mb-1">Total de Jugadores: {{ $equipo->jugadores->count() }}</p>
                        <div class="text-center mt-3">
                            <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-outline-light m-1">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            @auth
                                @can('Editar Equipos')
                                    <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary m-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endcan
                                @can('Borrar Equipos')
                                    <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger m-1 delete-equipo" data-id="{{ $equipo->id }}">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan
                            @endauth                            
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Vista de Lista (Bootstrap Table) -->
    <div id="list-view" class="d-none">
        <table 
            id="equipos-table"
            class="table table-striped table-hover"
            data-toggle="table"
            data-pagination="true"
            data-page-size="10"
            data-locale="es-ES"
            data-classes="table table-hover">
            <thead>
                <tr>
                    <th data-field="logo" data-sortable="false">Logo</th>
                    <th data-field="nombre" data-sortable="true">Nombre</th>
                    <th data-field="estado" data-sortable="true">Estado</th>
                    <th data-field="colores" data-sortable="false">Colores</th>
                    <th data-field="jugadores" data-sortable="true">Jugadores</th>
                    <th data-field="acciones" data-sortable="false">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipos as $equipo)
                    <tr class="equipo-item" 
                        data-nombre="{{ $equipo->nombre }}" 
                        data-estado="{{ $equipo->estado ? 'Activo' : 'Inactivo' }}"
                        data-jugadores="{{ $equipo->jugadores->count() }}">
                        <td>
                            <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid" style="max-height: 60px; max-width: 60px;">
                        </td>
                        <td>{{ $equipo->nombre }}</td>
                        <td>
                            <span class="badge {{ $equipo->estado ? 'bg-success' : 'bg-danger' }}">
                                {{ $equipo->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <span style="color: {{ $equipo->color_primario }}; font-size: 24px;">■</span>
                            @if($equipo->color_secundario)
                                <span style="color: {{ $equipo->color_secundario }}; font-size: 24px;">■</span>
                            @endif
                        </td>
                        <td>
                            <div>Total: {{ $equipo->jugadores->count() }}</div>
                            <div>Habilidosos: {{ $equipo->jugadores->where('tipo', 'habilidoso')->count() }}</div>
                            <div>Brazalete: {{ $equipo->jugadores->where('tipo', 'brazalete')->count() }}</div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @auth
                                    @can('Editar Equipos')
                                        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('Borrar Equipos')
                                        <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm delete-equipo" data-id="{{ $equipo->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
                                @endauth
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mensaje de No Resultados -->
    <div id="no-results" class="alert alert-info d-none">
        No se encontraron equipos que coincidan con tu búsqueda.
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Bootstrap Table para la vista de lista
        $('#equipos-table').bootstrapTable({
            search: false // Desactivamos la búsqueda integrada para usar nuestro campo personalizado
        });

        // Variables para los elementos DOM
        const gridViewBtn = document.getElementById('grid-view-btn');
        const listViewBtn = document.getElementById('list-view-btn');
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const searchInput = document.getElementById('search-input');
        const noResults = document.getElementById('no-results');
        const equipoItems = document.querySelectorAll('.equipo-item');
        const sortOptions = document.querySelectorAll('.sort-option');

        // Inicializar el dropdown de Bootstrap manualmente
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Función para cambiar entre vistas
        function toggleView(showGrid) {
            if (showGrid) {
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                localStorage.setItem('equiposViewPreference', 'grid');
            } else {
                gridView.classList.add('d-none');
                listView.classList.remove('d-none');
                gridViewBtn.classList.remove('active');
                listViewBtn.classList.add('active');
                localStorage.setItem('equiposViewPreference', 'list');
                // Ajustar columnas de la tabla cuando se muestra
                $('#equipos-table').bootstrapTable('resetView');
            }
        }

        // Event listeners para los botones de vista
        gridViewBtn.addEventListener('click', function() {
            toggleView(true);
        });

        listViewBtn.addEventListener('click', function() {
            toggleView(false);
        });

        // Cargar preferencia guardada
        const savedViewPreference = localStorage.getItem('equiposViewPreference');
        if (savedViewPreference === 'list') {
            toggleView(false);
        } else {
            toggleView(true);
        }

        // Función de búsqueda unificada
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let hasResults = false;

            // Buscar en la vista de cuadrícula
            const gridItems = gridView.querySelectorAll('.equipo-item');
            gridItems.forEach(item => {
                const nombre = item.getAttribute('data-nombre').toLowerCase();
                const estado = item.getAttribute('data-estado').toLowerCase();
                
                if (nombre.includes(searchTerm) || estado.includes(searchTerm)) {
                    item.classList.remove('d-none');
                    hasResults = true;
                } else {
                    item.classList.add('d-none');
                }
            });

            // Buscar en la tabla (Bootstrap Table)
            // Usamos la API de Bootstrap Table para filtrar
            const tableItems = $('#equipos-table tbody tr');
            tableItems.each(function() {
                const row = $(this);
                const nombre = row.find('td:nth-child(2)').text().toLowerCase();
                const estado = row.find('td:nth-child(3)').text().toLowerCase();
                
                if (nombre.includes(searchTerm) || estado.includes(searchTerm)) {
                    row.show();
                    hasResults = true;
                } else {
                    row.hide();
                }
            });

            // Mostrar/ocultar mensaje de no resultados
            if (hasResults || searchTerm === '') {
                noResults.classList.add('d-none');
            } else {
                noResults.classList.remove('d-none');
            }
        }

        // Event listeners para la búsqueda
        searchInput.addEventListener('keyup', performSearch);

        // Función para ordenar elementos
        function sortItems(sortType) {
            // Ordenar elementos en la vista de cuadrícula
            const gridItems = Array.from(gridView.querySelectorAll('.equipo-item'));
            
            gridItems.sort((a, b) => {
                const [field, direction] = sortType.split('-');
                const asc = direction === 'asc';
                
                if (field === 'nombre') {
                    const nameA = a.getAttribute('data-nombre').toLowerCase();
                    const nameB = b.getAttribute('data-nombre').toLowerCase();
                    return asc ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                } else if (field === 'jugadores') {
                    const playersA = parseInt(a.getAttribute('data-jugadores'));
                    const playersB = parseInt(b.getAttribute('data-jugadores'));
                    return asc ? playersA - playersB : playersB - playersA;
                } else if (field === 'estado') {
                    const statusA = a.getAttribute('data-estado');
                    const statusB = b.getAttribute('data-estado');
                    if (asc) {
                        return statusA === 'Inactivo' && statusB === 'Activo' ? -1 : 1;
                    } else {
                        return statusA === 'Activo' && statusB === 'Inactivo' ? -1 : 1;
                    }
                }
                return 0;
            });
            
            // Reordenar elementos en el DOM
            gridItems.forEach(item => {
                gridView.appendChild(item);
            });
            
            // Ordenar la tabla
            const [field, direction] = sortType.split('-');
            $('#equipos-table').bootstrapTable('sortBy', {
                field: field === 'jugadores' ? 'jugadores' : (field === 'estado' ? 'estado' : 'nombre'),
                sortOrder: direction
            });
        }

        // Event listeners para las opciones de ordenamiento
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const sortType = this.getAttribute('data-sort');
                sortItems(sortType);
            });
        });

        // Confirmación de eliminación con SweetAlert2
        const deleteButtons = document.querySelectorAll('.delete-equipo');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const equipoId = this.getAttribute('data-id');
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

<style>
    /* Estilos para los botones activos */
    .btn-group .btn.active {
        background-color: #FFD54F;
        color: #212121;
        border-color: #FFD54F;
    }
    
    /* Estilos para la vista de cuadrícula */
    #grid-view .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    #grid-view .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    /* Estilos para la tabla */
    #equipos-table img {
        transition: transform 0.3s ease;
    }
    
    #equipos-table img:hover {
        transform: scale(1.1);
    }
    
    /* Estilos para el dropdown de ordenamiento */
    .dropdown-menu {
        background-color: #2a2a2a;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .dropdown-item {
        color: #fff;
    }
    
    .dropdown-item:hover, .dropdown-item:focus {
        background-color: #3a3a3a;
        color: #FFD54F;
    }
    
    /* Estilos para la paginación */
    .pagination {
        justify-content: center;
        margin-top: 20px;
    }
    
    .page-link {
        background-color: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }
    
    .page-link:hover {
        background-color: #3a3a3a;
        border-color: #4a4a4a;
        color: #FFD54F;
    }
    
    .page-item.active .page-link {
        background-color: #FFD54F;
        border-color: #FFD54F;
        color: #212121;
    }
</style>
@endpush

