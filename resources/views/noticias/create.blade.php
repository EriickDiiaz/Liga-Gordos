@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Crear Nueva Noticia</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="titulo">Titulo de la Noticia</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="form-group mb-3">
                    <label for="contenido">Contenido de la Noticia</label>
                    <textarea class="form-control" id="contenido" name="contenido" rows="5" required></textarea>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('noticias.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Crear Jugadores')
                    <button type="submit" class="btn btn-outline-success m-1">Crear Noticia</button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection