@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Roles y Permisos</h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Roles</h2>
            <table class="table">
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
                                <span class="badge bg-primary">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-role" data-role="{{ $role->id }}">Editar</button>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h3 class="mt-4">Crear Nuevo Rol</h3>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Rol</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Permisos</label>
                    @foreach($permissions as $permission)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary">Crear Rol</button>
            </form>
        </div>

        <div class="col-md-6">
            <h2>Permisos</h2>
            <ul class="list-group">
                @foreach($permissions as $permission)
                    <li class="list-group-item">{{ $permission->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Modal for editing roles -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Editar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permisos</label>
                        <div id="edit_permissions"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.edit-role').click(function() {
            var roleId = $(this).data('role');
            var url = "{{ route('roles.update', ':id') }}".replace(':id', roleId);
            $('#editRoleForm').attr('action', url);

            $.get("{{ route('roles.edit', ':id') }}".replace(':id', roleId), function(data) {
                $('#edit_name').val(data.name);
                var permissionsHtml = '';
                data.permissions.forEach(function(permission) {
                    permissionsHtml += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="${permission.id}" id="edit_permission_${permission.id}" ${permission.checked ? 'checked' : ''}>
                            <label class="form-check-label" for="edit_permission_${permission.id}">
                                ${permission.name}
                            </label>
                        </div>
                    `;
                });
                $('#edit_permissions').html(permissionsHtml);
                $('#editRoleModal').modal('show');
            });
        });
    });
</script>
@endpush

