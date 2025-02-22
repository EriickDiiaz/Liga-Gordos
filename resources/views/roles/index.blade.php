@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gestión de Roles y Permisos</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Roles</h2>
            <a href="{{ route('roles.create') }}" class="btn btn-outline-success">Crear Nuevo Rol</a>
        </div>

        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-primary me-1">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary d-inline">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger m-1 delete-role" data-id="{{ $role->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Permisos</h2>
            <a href="{{ route('permissions.create') }}" class="btn btn-outline-success">Crear Nuevo Permiso</a>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Roles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            @foreach($permission->roles as $role)
                                <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-outline-primary d-inline">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger m-1 delete-permission" data-id="{{ $permission->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteRoleButtons = document.querySelectorAll('.delete-role');
        const deletePermissionButtons = document.querySelectorAll('.delete-permission');

        function setupDeleteButtons(buttons, type) {
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: `No podrás revertir esta acción! Se eliminará este ${type}.`,
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
        }

        setupDeleteButtons(deleteRoleButtons, 'rol');
        setupDeleteButtons(deletePermissionButtons, 'permiso');
    });
</script>
@endpush

