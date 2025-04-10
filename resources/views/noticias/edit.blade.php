@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3 text-center">Editar Noticia: {{ $noticia->titulo }}</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('noticias.update', $noticia) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="titulo">Titulo de la Noticia</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $noticia->titulo }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="contenido">Contenido de la Noticia</label>
                    <textarea class="form-control" id="contenido" name="contenido" rows="5" required>{{ $noticia->contenido }}</textarea>
                </div>               
                <div class="text-center mt-3">
                    <a href="{{ route('noticias.index') }}" class="btn btn-outline-secondary m-1">
                        <i class="fas fa-arrow-left"></i> Volver a la lista
                    </a>
                    @can('Editar Noticias')
                    <button type="submit" class="btn btn-outline-primary m-1">Actualizar Noticia</button>
                    @endcan
                </div>        
            </form>
        </div>
    </div>
</div>
@endsection

