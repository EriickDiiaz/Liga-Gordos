@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Equipo: {{ $equipo->nombre }}</h1>
    <form action="{{ route('equipos.update', $equipo) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre del Equipo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $equipo->nombre }}" required>
        </div>
        <div class="form-group">
            <label for="color_primario">Color Primario</label>
            <input type="color" class="form-control" id="color_primario" name="color_primario" value="{{ $equipo->color_primario }}" required>
        </div>
        <div class="form-group">
            <label for="color_secundario">Color Secundario (opcional)</label>
            <input type="color" class="form-control" id="color_secundario" name="color_secundario" value="{{ $equipo->color_secundario }}">
        </div>
        <div class="form-group">
            <label for="logo">Logo del Equipo</label>
            <input type="file" class="form-control-file" id="logo" name="logo">
            @if($equipo->logo)
                <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="mt-2" style="max-width: 200px;">
            @endif
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado">
                <option value="1" {{ $equipo->estado ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ !$equipo->estado ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Actualizar Equipo</button>
    </form>
</div>
@endsection