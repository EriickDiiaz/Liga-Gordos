@extends('layouts.app')
@section('title', 'Editar Equipo')
@section('content')

<!-- Mensajes y alertas -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">

    <!-- Titulo -->
    <div>
        <h1 class="mb-3 text-center"><i class="fa-solid fa-shield me-2"></i>Editar Equipo: {{ $equipo->nombre }}</h1>
    </div>

    <!-- Contenido -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('equipos.update', $equipo) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Equipo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $equipo->nombre }}" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color_primario">Color Primario</label>
                            <input type="color" class="form-control" id="color_primario" name="color_primario" value="{{ $equipo->color_primario }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color_secundario">Color Secundario (opcional)</label>
                            <input type="color" class="form-control" id="color_secundario" name="color_secundario" value="{{ $equipo->color_secundario }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">Instagram (Opcional)</label>
                            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $equipo->instagram }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tiktok">Tik-Tok (opcional)</label>
                            <input type="text" class="form-control" id="tiktok" name="tiktok" value="{{ $equipo->tiktok }}">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="logo">Logo del Equipo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                    @if($equipo->logo)
                        <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="mt-2" style="max-width: 100px;">
                    @endif
                </div>
                <div class="form-group mb-3">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="1" {{ $equipo->estado ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$equipo->estado ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Equipos')  
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrows-rotate me-2"></i>Actualizar Equipo
                    </button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection

