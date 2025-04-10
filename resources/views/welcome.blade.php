@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="hero-section position-relative">
        <div class="hero-image" style="background: linear-gradient(#FFD54F, #ff9a02); height: 500px;">
            <div class="container h-100 d-flex flex-column justify-content-center text-center text-dark">
                <div class="text-center">
                    <img src="{{ asset('img/liga-gordos-logo.png') }}" alt="Liga de Gordos Logo" class="img-fluid">
                </div>
                <h1 class="display-3 fw-bold mb-4">Liga de los Gordos</h1>
                <p class="lead mb-4">Donde la pasión por el fútbol se une con la amistad y la competencia sana</p>
            </div>
        </div>
    </div>

    <!-- Upcoming Matches Section -->
    <section class="py-5 bg-dark">
        <div class="container">
            <h2 class="text-center mb-5 text-white">Próximos Partidos</h2>
            
            <div class="row">
                @if($proximosPartidos->count() > 0)
                    @foreach($proximosPartidos as $partido)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 bg-dark text-white border">
                                <div class="card-header text-center">
                                    <h5>
                                        @if($partido->torneo)
                                            {{ $partido->torneo->nombre }}
                                        @else
                                            Partido Amistoso
                                        @endif
                                    </h5>
                                    <span class="badge bg-primary">{{ $partido->fecha->format('d/m/Y h:i A') }}</span>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row align-items-center">
                                        <div class="col-5 text-center">
                                            <img src="{{ asset($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" class="img-fluid mb-2" style="max-height: 80px;">
                                            <h6>{{ $partido->equipoLocal->nombre }}</h6>
                                        </div>
                                        <div class="col-2">
                                            <span class="display-6">V</span>
                                        </div>
                                        <div class="col-5 text-center">
                                            <img src="{{ asset($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" class="img-fluid mb-2" style="max-height: 80px;">
                                            <h6>{{ $partido->equipoVisitante->nombre }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('partidos.show', $partido) }}" class="btn btn-outline-light">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p class="text-white">No hay partidos programados próximamente.</p>
                    </div>
                @endif
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('partidos.index') }}" class="btn btn-outline-warning">Ver Todos los Partidos</a>
            </div>
        </div>
    </section>

    <!-- Sponsors Section -->
    <section class="py-5" style="background: linear-gradient(#FFD54F, #ff9a02);">
        <div class="container">
            <h2 class="text-center mb-5 text-dark">Nuestros Patrocinadores</h2>
        
            <div class="teams-container">
                @if(isset($patrocinadores) && $patrocinadores->count() > 0)
                    <div class="teams-grid">
                        @foreach($patrocinadores as $patrocinador)
                            <a href="{{ route('patrocinador.index', $patrocinador) }}" class="team-circle-link">
                                <div class="team-circle" style="background-image: url('{{ asset($patrocinador->logo) }}'); border: 3px solid #ff9a02;">
                                    <div class="team-overlay" style="background: linear-gradient(rgba(0,0,0,0.7), #ff9a02);">
                                        <div class="team-name">{{ $patrocinador->nombre }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-dark">
                        <p>No hay patrocinadores registrados actualmente.</p>
                    </div>
                @endif
            </div>
        
            <div class="text-center mt-4">
                <a href="{{ route('patrocinador.index') }}" class="btn btn-outline-dark">Ver Todos los Patrocinadores</a>
            </div>
        </div>
    </section>

    <!-- Teams Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Equipos Participantes</h2>
            
            <div class="teams-container">
                @if($equipos->count() > 0)
                    <div class="teams-grid">
                        @foreach($equipos as $equipo)
                            <a href="{{ route('equipos.show', $equipo) }}" class="team-circle-link">
                                <div class="team-circle" style="background-image: url('{{ asset($equipo->logo) }}'); border: 3px solid {{ $equipo->color_primario }};">
                                    <div class="team-overlay" style="background: linear-gradient(rgba(0,0,0,0.7), {{ $equipo->color_primario }});">
                                        <div class="team-name">{{ $equipo->nombre }}</div>
                                        <div class="team-players">{{ $equipo->jugadores->count() }} jugadores</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center">
                        <p>No hay equipos registrados actualmente.</p>
                    </div>
                @endif
            </div>
            
            <div class="text-center mt-5">
                <div class="stats-box p-4 d-inline-block bg-dark text-white rounded">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <i class="fas fa-shield-alt fa-2x mb-2 text-warning"></i>
                            <h3>{{ $equipos->count() }}</h3>
                            <p class="mb-0">Equipos</p>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <i class="fas fa-running fa-2x mb-2 text-warning"></i>
                            <h3>{{ App\Models\Jugador::count() }}</h3>
                            <p class="mb-0">Jugadores</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-trophy fa-2x mb-2 text-warning"></i>
                            <h3>{{ $torneosActivos }}</h3>
                            <p class="mb-0">Torneos Activos</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('equipos.index') }}" class="btn btn-outline-warning">Ver Todos los Equipos</a>
            </div>
        </div>
    </section>

    <!-- Tournament Rules Section -->
    <section class="py-5" style="background: linear-gradient(#FFD54F, #ff9a02);">
        <div class="container">
            <h2 class="text-center text-dark mb-5">Reglas del Torneo</h2>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-users me-2 text-warning"></i>Equipos y Jugadores</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Cada equipo debe tener un mínimo de 7 jugadores y un máximo de 15.</li>
                                <li class="list-group-item">Es obligatorio tener al menos un portero por equipo.</li>
                                <li class="list-group-item">Los jugadores deben ser mayores de 18 años.</li>
                                <li class="list-group-item">Cada equipo debe designar un capitán que será el responsable de la comunicación con la organización.</li>
                                <li class="list-group-item">Los equipos deben presentarse con uniforme completo (camiseta, pantalón y medias).</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-futbol me-2 text-warning"></i>Partidos</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Los partidos tendrán una duración de 40 minutos, divididos en dos tiempos de 20 minutos.</li>
                                <li class="list-group-item">El descanso entre tiempos será de 5 minutos.</li>
                                <li class="list-group-item">Cada equipo puede realizar cambios ilimitados durante el partido.</li>
                                <li class="list-group-item">Los partidos comenzarán a la hora programada. Se dará un margen de 10 minutos, después de los cuales se declarará incomparecencia.</li>
                                <li class="list-group-item">La incomparecencia se sancionará con la pérdida del partido por 3-0.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-trophy me-2 text-warning"></i>Sistema de Competición</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">La liga se jugará en formato de grupos seguido de eliminatorias.</li>
                                <li class="list-group-item">En la fase de grupos, cada equipo jugará contra todos los demás equipos de su grupo una vez.</li>
                                <li class="list-group-item">Los puntos se asignarán de la siguiente manera: 3 puntos por victoria, 1 punto por empate, 0 puntos por derrota.</li>
                                <li class="list-group-item">Los dos primeros equipos de cada grupo avanzarán a la fase eliminatoria.</li>
                                <li class="list-group-item">En caso de empate en puntos, se tendrán en cuenta: 1) Diferencia de goles, 2) Goles a favor, 3) Resultado del enfrentamiento directo.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-gavel me-2 text-warning"></i>Disciplina</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Las tarjetas amarillas supondrán una amonestación.</li>
                                <li class="list-group-item">Dos tarjetas amarillas en un mismo partido supondrán la expulsión del jugador.</li>
                                <li class="list-group-item">La tarjeta roja directa supondrá la expulsión del jugador y al menos un partido de sanción.</li>
                                <li class="list-group-item">La acumulación de 3 tarjetas amarillas en diferentes partidos supondrá un partido de sanción.</li>
                                <li class="list-group-item">Las conductas antideportivas graves podrán ser sancionadas con la expulsión del torneo.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Últimas Noticias</h2>

                @if($ultimasNoticias->count() > 0)
                    <div class="row">
                        @foreach ($ultimasNoticias as $noticia)
                            <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <i class=""></i>
                                <div class="card-body">
                                    <h4 class="card-title"><i class="fas fa-solid fa-newspaper me-2 text-warning"></i>{{ $noticia->titulo }}</h4>
                                    <p class="card-text">{{ $noticia->contenido }}</p>
                                    <p class="card-text"><small class="text-muted">Publicado el {{ $noticia->created_at->format('d/m/Y H:M') }}</small></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>                    
                @else
                    <div class="col-12 text-center">
                        <p class="text-white">No hay Noticias recientes.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 text-black" style="background: linear-gradient(#FFD54F, #ff9a02);">
        <div class="container text-center">
            <h2 class="mb-4">¿Quieres unirte a la Liga de los Gordos?</h2>
            <p class="lead mb-4">Si tienes un equipo o quieres formar parte de uno, ¡contáctanos!</p>
            <a href="#" class="btn btn-outline-dark btn-lg">Contáctanos</a>
        </div>
    </section>
</div>

<style>
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    
    .stats-box {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Estilos para los equipos y patrocinadores */
    .teams-container {
        padding: 20px 0;
        overflow: hidden;
    }
    
    .teams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        justify-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .team-circle-link {
        text-decoration: none;
        display: block;
    }
    
    .team-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: white;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .team-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50%;
    }
    
    .team-name {
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 5px 10px;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .team-players {
        color: white;
        font-size: 0.8rem;
        text-align: center;
        padding: 0 10px;
        transform: translateY(20px);
        transition: transform 0.3s ease 0.1s;
    }
    
    .team-circle:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    
    .team-circle:hover .team-overlay {
        opacity: 1;
    }
    
    .team-circle:hover .team-name,
    .team-circle:hover .team-players {
        transform: translateY(0);
    }
    
    /* Responsive adjustments for teams */
    @media (max-width: 1200px) {
        .teams-grid {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        }
        .team-circle {
            width: 130px;
            height: 130px;
        }
    }
    
    @media (max-width: 992px) {
        .teams-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }
        .team-circle {
            width: 120px;
            height: 120px;
        }
    }
    
    @media (max-width: 768px) {
        .teams-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }
        .team-circle {
            width: 100px;
            height: 100px;
        }
        .team-name {
            font-size: 0.9rem;
        }
        .team-players {
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 576px) {
        .teams-grid {
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        }
        .team-circle {
            width: 80px;
            height: 80px;
        }
        .team-name {
            font-size: 0.8rem;
        }
        .team-players {
            font-size: 0.6rem;
        }
    }
</style>
@endsection
