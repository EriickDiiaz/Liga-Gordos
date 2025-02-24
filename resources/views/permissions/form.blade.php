@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">{{ isset($permission) ? 'Editar Permiso' : 'Crear Nuevo Permiso' }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ isset($permission) ? route('permissions.update', $permission) : route('permissions.store') }}" method="POST">
                @csrf
                @if(isset($permission))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Permiso</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name ?? old('name') }}" required>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary m-1">Volver a la lista</a>
                    <button type="submit" class="btn btn-outline-primary m-1">{{ isset($permission) ? 'Actualizar' : 'Crear' }} Permiso</button>
                </div>                
            </form>
        </div>
    </div>
</div>
@endsection

