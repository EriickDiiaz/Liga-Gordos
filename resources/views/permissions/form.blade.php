@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ isset($permission) ? 'Editar Permiso' : 'Crear Nuevo Permiso' }}</h1>

    <form action="{{ isset($permission) ? route('permissions.update', $permission) : route('permissions.store') }}" method="POST">
        @csrf
        @if(isset($permission))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Permiso</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name ?? old('name') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($permission) ? 'Actualizar' : 'Crear' }} Permiso</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

