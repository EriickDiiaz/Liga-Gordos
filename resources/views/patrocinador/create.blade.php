@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Crear Nuevo Patrocinante</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('patrocinador.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="nombre">Nombre del patrocinantes</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group mb-3">
                    <label for="logo">Logo del Patrocinante</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">Instagram (opcional)</label>
                            <input type="text" class="form-control" id="instagram" name="instagram">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tiktok">Tik-Tok (opcional)</label>
                            <input type="text" class="form-control" id="tiktok" name="tiktok">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="facebook">Facebook (opcional)</label>
                            <input type="text" class="form-control" id="facebook" name="facebook">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">Teléfono (opcional)</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('patrocinador.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Crear Patrocinadores')
                    <button type="submit" class="btn btn-outline-success m-1">Crear Patrocinador</button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection

