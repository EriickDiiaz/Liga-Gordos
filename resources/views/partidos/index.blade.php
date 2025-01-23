@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Partidos</h1>
    
    <a href="{{ route('partidos.create') }}" class="btn btn-primary mb-3">Crear Nuevo Partido</a>

    <table class="table">
        <thead>
            <tr>
                <th>Torneo</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partidos as $partido)
                <tr>
                    <td>{{ $partido->torneo->nombre }}</td>
                    <td>{{ $partido->equipoLocal->nombre }}</td>
                    <td>{{ $partido->equipoVisitante->nombre }}</td>
                    <td>{{ $partido->fecha->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($partido->estado) }}</td>
                    <td>
                        <a href="{{ route('partidos.show', $partido) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

