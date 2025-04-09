@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Editar Patrocinador {{ $patrocinador->nombre }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('patrocinador.update', $patrocinador) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del Patrocinador</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $patrocinador->nombre }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="logo">Logo del Patrocinador</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                    @if($patrocinador->logo)
                        <img src="{{ asset($patrocinador->logo) }}" alt="{{ $patrocinador->nombre }}" class="mt-2" style="max-width: 100px;">
                    @endif
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">Instagram (Opcional)</label>
                            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $patrocinador->instagram }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tiktok">Tik-Tok (opcional)</label>
                            <input type="text" class="form-control" id="tiktok" name="tiktok" value="{{ $patrocinador->tiktok }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="facebook">Facebook (Opcional)</label>
                            <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $patrocinador->facebook }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">Teléfono (opcional)</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $patrocinador->telefono }}">
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('patrocinador.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Patrocinadores')  
                    <button type="submit" class="btn btn-outline-primary m-1">Actualizar Patrocinador</button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection
