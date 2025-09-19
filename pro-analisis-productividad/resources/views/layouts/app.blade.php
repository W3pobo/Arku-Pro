<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ArkuPro')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #3498db; --secondary: #2c3e50; --success: #2ecc71;
            --warning: #f39c12; --danger: #e74c3c; --light: #ecf0f1;
            --dark: #34495e; --gray: #95a5a6;
        }
        body { background-color: #f5f7fa; color: #333; font-family: 'Segoe UI', sans-serif; }
        .btn { border-radius: 5px; font-weight: 500; text-decoration: none; }
        .btn-danger { background-color: var(--danger); color: white; }
        .logout-form { display: inline; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 1em; border-radius: 4px; margin-bottom: 1em; }
        /* Puedes agregar más estilos globales aquí */
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        {{-- TU BARRA DE NAVEGACIÓN PERSONALIZADA CON LA LÓGICA DE AUTH --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                    <strong style="color: var(--primary); font-size: 1.5rem;">
                        Arku<span style="color: var(--secondary);">Pro</span>
                    </strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            {{-- SI EL USUARIO ES VISITANTE --}}
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Crear Cuenta</a>
                                </li>
                            @endif
                        @else
                            {{-- SI EL USUARIO ESTÁ AUTENTICADO --}}
                            <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">Proyectos</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('time-trackings.index') }}">Tiempo</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}">Reportes</a></li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>