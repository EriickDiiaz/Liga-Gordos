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
        <div class="card-header">
            <h2 class="mb-0">Roles</h2>
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
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este rol?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('roles.create') }}" class="btn btn-success mt-3">Crear Nuevo Rol</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Permisos</h2>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($permissions as $permission)
                    <li class="list-group-item">{{ $permission->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

