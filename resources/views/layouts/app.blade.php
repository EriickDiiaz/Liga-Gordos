<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS (Dark mode) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --soft-yellow: #FFF9C4;
            --mustard: #FFD54F;
            --black: #212121;
        }

        .navbar-custom {
            background-color: var(--black);
        }

        .navbar-custom .navbar-brand img {
            max-height: 40px;
        }

        .navbar-custom .nav-link {
            color: var(--soft-yellow);
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link:focus {
            color: var(--mustard);
        }

        .navbar-custom .navbar-toggler {
            border-color: var(--soft-yellow);
        }

        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 249, 196, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .btn-custom {
            background-color: var(--mustard);
            color: var(--black);
            border: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover,
        .btn-custom:focus {
            background-color: var(--soft-yellow);
            color: var(--black);
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        #app {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1 0 auto;
        }
        
        .footer {
            flex-shrink: 0;
            border-top: 3px solid var(--mustard);
        }
        
        .social-icons a:hover {
            color: var(--mustard) !important;
            transform: scale(1.2);
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--mustard) !important;
            transition: color 0.3s ease;
        }

        .footer-logo {
            max-height: 50px; /* Ajusta el tamaño según sea necesario */
            width: auto; /* Mantiene la proporción de la imagen */
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-custom shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/liga-gordos-logo.png') }}" alt="Liga de Gordos Logo" class="img-fluid">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{'Toggle navigation'}}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('equipos.index') }}">Equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jugador.index') }}">Jugadores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('torneos.index') }}">Torneos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('partidos.index') }}">Partidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patrocinador.index') }}">Patrocinadores</a>
                        </li>
                        @can('gestionar roles y permisos')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('roles.index') }}">Roles y Permisos</a>
                        </li>
                        @endcan
                        @can('gestionar usuarios')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a>
                        </li>
                        @endcan
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-custom mx-1" href="{{ route('login') }}">Iniciar Sesión</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>                        
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="footer mt-auto py-3" style="background-color: var(--black);">
            <div class="container text-center">
                <div class="row">
                    <ul class="list-unstyled">
                        <li>© 2025 Liga de los Gordos</li>
                        <li>Liga de los Gordos ha sido desarrollado por <a href="mailto:erick.diaz.1.2@gmail.com" style="color: var(--mustard);">Erick Diaz</a> y Viking Team Web Services</li>
                        <li><a href="https://wa.me/+584122122246" class="text-decoration-none" style="color: var(--soft-yellow);"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a></li>
                        <li><a href="https://www.instagram.com/vikingteamvzla/"><img src="{{ asset('img/viking-team-logo.png') }}" alt="Viking Team Logo" class="footer-logo"></a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Configuración global para DataTables
        $.extend(true, $.fn.dataTable.defaults, {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "responsive": true,
            "pageLength": 10
        });
        
        // Configurar CSRF token para AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
