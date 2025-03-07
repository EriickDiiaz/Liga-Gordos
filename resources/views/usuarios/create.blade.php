@extends('layouts.app')

@section('content')

<!-- Mensajes y Notificaciones -->
@if ($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <strong>¡Uy!</strong> Revisa los siguientes errores antes de continuar.
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container">
    <h1 class="mb-3 text-center">Crear Nuevo Usuario</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Usuario</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label for="roles" class="form-label">Rol</label>
                    <select class="form-control @error('roles') is-invalid @enderror" id="roles" name="roles" required>
                        <option value="">Seleccione un rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-role-name="{{ $role->name }}" {{ old('roles') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="equipo-select" style="display: none;">
                    <label for="equipo_id" class="form-label">Equipo</label>
                    <select class="form-control @error('equipo_id') is-invalid @enderror" id="equipo_id" name="equipo_id">
                        <option value="">Seleccione un equipo</option>
                        @foreach($equipos as $equipo)
                            <option value="{{ $equipo->id }}" {{ old('equipo_id') == $equipo->id ? 'selected' : '' }}>{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('equipo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
    $(document).ready(function() {
        // Función para mostrar/ocultar el selector de equipo
        function toggleEquipoSelect() {
            var selectedOption = $('#roles option:selected');
            var isCapitan = selectedOption.data('role-name') === 'Capitán';
            
            if (isCapitan) {
                $('#equipo-select').show();
            } else {
                $('#equipo-select').hide();
            }
        }

        // Ejecutar al cargar la página para manejar valores antiguos
        toggleEquipoSelect();

        // Ejecutar cuando cambie el select de roles
        $('#roles').change(toggleEquipoSelect);
    });
</script>
@endpush

