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
                            <div class="card h-100 bg-dark text-white border-warning">
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
        
            <div class="sponsors-container">
                @if(isset($patrocinadores) && $patrocinadores->count() > 0)
                    <div class="sponsors-grid">
                        @foreach($patrocinadores as $patrocinador)
                            <a href="{{ route('patrocinador.index') }}" class="sponsor-circle-link">
                                <div class="sponsor-circle" style="background-image: url('{{ asset($patrocinador->logo) }}')">
                                    <div class="sponsor-overlay">
                                        <div class="sponsor-name">{{ $patrocinador->nombre }}</div>
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
            
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($equipos as $equipo)
                    <div class="col">
                        <div class="card h-100 team-card">
                            <div class="card-header" style="background-color: {{ $equipo->color_primario }}; height: 10px;"></div>
                            <div class="card-body text-center">
                                <img src="{{ asset($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="img-fluid mb-3" style="max-height: 100px;">
                                <h5 class="card-title">{{ $equipo->nombre }}</h5>
                                <p class="card-text">
                                    <small>Jugadores: {{ $equipo->jugadores->count() }}</small>
                                </p>
                            </div>
                            <div class="card-footer text-center" style="background-color: {{ $equipo->color_secundario ?? $equipo->color_primario }}; height: 10px;">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <div class="stats-box p-4 d-inline-block bg-dark text-white rounded">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <h3>{{ $equipos->count() }}</h3>
                            <p class="mb-0">Equipos</p>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <h3>{{ App\Models\Jugador::count() }}</h3>
                            <p class="mb-0">Jugadores</p>
                        </div>
                        <div class="col-md-4 text-center">
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
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/news-1.jpg') }}" class="card-img-top" alt="Noticia 1">
                        <div class="card-body">
                            <h5 class="card-title">Inauguración de la Temporada 2023</h5>
                            <p class="card-text">La nueva temporada de la Liga de los Gordos comenzará el próximo 15 de marzo con un partido inaugural entre los finalistas del año pasado.</p>
                            <p class="card-text"><small class="text-muted">Publicado el 1 de marzo, 2023</small></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/news-2.jpg') }}" class="card-img-top" alt="Noticia 2">
                        <div class="card-body">
                            <h5 class="card-title">Nuevas Instalaciones</h5>
                            <p class="card-text">La liga estrena nuevas instalaciones con césped artificial de última generación y vestuarios renovados para mayor comodidad de los jugadores.</p>
                            <p class="card-text"><small class="text-muted">Publicado el 15 de febrero, 2023</small></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('img/news-3.jpg') }}" class="card-img-top" alt="Noticia 3">
                        <div class="card-body">
                            <h5 class="card-title">Nuevos Equipos se Unen a la Liga</h5>
                            <p class="card-text">Cinco nuevos equipos se han unido a nuestra liga para la temporada 2023, elevando el nivel de competición y emoción.</p>
                            <p class="card-text"><small class="text-muted">Publicado el 5 de febrero, 2023</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 text-black" style="background: linear-gradient(#FFD54F, #ff9a02);">
        <div class="container text-center">
            <h2 class="mb-4">¿Quieres unirte a la Liga de los Gordos?</h2>
            <p class="lead mb-4">Si tienes un equipo o quieres formar parte de uno, ¡contáctanos!</p>
            <a href="#" class="btn btn-outline-light btn-lg">Contáctanos</a>
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

    /* Estilos para los patrocinadores */
    .sponsors-container {
        padding: 20px 0;
        overflow: hidden;
    }
    
    .sponsors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        justify-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .sponsor-circle-link {
        text-decoration: none;
        display: block;
    }
    
    .sponsor-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: white;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .sponsor-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50%;
    }
    
    .sponsor-name {
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .sponsor-circle:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    
    .sponsor-circle:hover .sponsor-overlay {
        opacity: 1;
    }
    
    .sponsor-circle:hover .sponsor-name {
        transform: translateY(0);
    }
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .sponsors-grid {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        }
        .sponsor-circle {
            width: 130px;
            height: 130px;
        }
    }
    
    @media (max-width: 992px) {
        .sponsors-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }
        .sponsor-circle {
            width: 120px;
            height: 120px;
        }
    }
    
    @media (max-width: 768px) {
        .sponsors-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }
        .sponsor-circle {
            width: 100px;
            height: 100px;
        }
    }
    
    @media (max-width: 576px) {
        .sponsors-grid {
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        }
        .sponsor-circle {
            width: 80px;
            height: 80px;
        }
    }
</style>
@endsection
