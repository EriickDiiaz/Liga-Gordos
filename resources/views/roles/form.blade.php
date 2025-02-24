@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">{{ isset($role) ? 'Editar Rol' : 'Crear Nuevo Rol' }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}" method="POST">
                @csrf
                @if(isset($role))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Rol</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $role->name ?? old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permisos</label>
                    @foreach($permissions as $permission)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
                                {{ isset($role) && $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary m-1">Volver a la lista</a>
                    <button type="submit" class="btn btn-outline-primary m-1">{{ isset($role) ? 'Actualizar' : 'Crear' }} Rol</button>
                </div>                
            </form>
        </div>
    </div>        
</div>
@endsection

