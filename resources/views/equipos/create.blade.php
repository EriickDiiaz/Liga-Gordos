@extends('layouts.app')
@section('title', 'Crear Equipo')
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
        <h1 class="mb-3 text-center"><i class="fa-solid fa-shield me-2"></i>Crear Nuevo Equipo</h1>
    </div>

    <!-- Contenido -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('equipos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Equipo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required value="{{ old('nombre') }}">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color_primario">Color Primario</label>
                            <input type="color" class="form-control" id="color_primario" name="color_primario" required value="{{ old('color_primario', '#ffffff') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="color_secundario">Color Secundario (opcional)</label>
                            <input type="color" class="form-control" id="color_secundario" name="color_secundario" value="{{ old('color_secundario', '#000000') }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">Instagram (opcional)</label>
                            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tiktok">Tik-Tok (opcional)</label>
                            <input type="text" class="form-control" id="tiktok" name="tiktok" value="{{ old('tiktok') }}">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="logo">Logo del Equipo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>
                <div class="form-group mb-3">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a la lista
                    </a>
                    @can('Crear Equipos')
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Equipo
                    </button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection

