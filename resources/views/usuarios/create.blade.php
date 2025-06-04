@extends('layouts.app')

@section('content')
<div class="container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="mb-3 text-center">Crear Nuevo Usuario</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Roles</label>
                    @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}">
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                        <label class="form-check-label" for="active">Usuario Activo</label>
                    </div>
                </div>

                <div class="mb-3" id="active_until_container">
                    <label for="active_until" class="form-label">Activo Hasta (opcional)</label>
                    <input type="date" class="form-control" id="active_until" name="active_until" value="{{ old('active_until') }}">
                    <div class="form-text">Deja este campo vacío para que el usuario no tenga fecha de expiración.</div>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary m-1">Volver a la lista</a>
                    <button type="submit" class="btn btn-outline-success m-1">Crear Usuario</button>                    
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeSwitch = document.getElementById('active');
        const activeUntilContainer = document.getElementById('active_until_container');

        function toggleActiveUntilVisibility() {
            if (activeSwitch.checked) {
                activeUntilContainer.style.display = 'block';
            } else {
                activeUntilContainer.style.display = 'none';
                document.getElementById('active_until').value = '';
            }
        }

        activeSwitch.addEventListener('change', toggleActiveUntilVisibility);
        toggleActiveUntilVisibility();
    });
</script>
@endpush

