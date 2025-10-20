<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ArkuPro')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <style>
        :root {
            --bg-primary: #121826;
            --bg-secondary: #2A3241;
            --accent: #7E57C2;
            --accent-hover: #9575CD;
            --text-primary: #F0F2F5;
            --text-secondary: #A9B4C7;
            --success: #22c55e;
            --warning: #eab308;
            --danger: #ef4444;
            --border-color: #3a4251;
        }
        
        body { 
            background-color: var(--bg-primary) !important; 
            color: var(--text-primary) !important; 
            font-family: 'Nunito', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%) !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }
        
        .navbar-brand {
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: translateY(-1px);
        }
        
        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            margin: 0 0.1rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--text-primary) !important;
            background-color: rgba(126, 87, 194, 0.1);
            transform: translateY(-1px);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), var(--accent-hover));
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .dropdown-menu {
            background-color: var(--bg-secondary) !important;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            padding: 0.5rem;
        }
        
        .dropdown-item {
            color: var(--text-secondary);
            border-radius: 8px;
            margin: 0.1rem 0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--accent) !important;
            color: var(--text-primary) !important;
            transform: translateX(5px);
        }
        
        .dropdown-divider {
            border-color: var(--border-color);
            margin: 0.5rem 0;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.9), rgba(34, 197, 94, 0.7));
            color: white;
        }
        
        .btn-close {
            filter: invert(1);
        }
        
        .navbar-toggler {
            border: 1px solid var(--border-color);
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* ===== ESTILOS PARA COMPONENTES BOOTSTRAP ===== */

        /* Cards */
        .card {
            background-color: var(--bg-secondary) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 12px !important;
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.2) !important;
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .card-footer {
            background-color: rgba(0, 0, 0, 0.2) !important;
            border-top: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        /* Tables */
        .table {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .table th {
            background-color: rgba(0, 0, 0, 0.3) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .table td {
            border-color: var(--border-color) !important;
            background-color: transparent !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(126, 87, 194, 0.1) !important;
            color: var(--text-primary) !important;
        }

        /* Forms */
        .form-control, .form-select {
            background-color: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(0, 0, 0, 0.4) !important;
            border-color: var(--accent) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25) !important;
        }

        .form-label {
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .form-control::placeholder {
            color: var(--text-secondary) !important;
            opacity: 0.7;
        }

        /* Buttons */
        .btn {
            border-radius: 8px !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover)) !important;
            border: none !important;
            color: white !important;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(126, 87, 194, 0.4) !important;
        }

        .btn-secondary {
            background-color: var(--border-color) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .btn-secondary:hover {
            background-color: #4a5261 !important;
            border-color: #4a5261 !important;
            color: var(--text-primary) !important;
        }

        .btn-outline-secondary {
            border-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
            background: transparent !important;
        }

        .btn-outline-secondary:hover {
            background-color: var(--border-color) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        /* Badges */
        .badge {
            border-radius: 20px !important;
            font-weight: 500;
            padding: 0.5rem 0.75rem !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover)) !important;
        }

        .bg-secondary {
            background-color: var(--border-color) !important;
        }

        .bg-success {
            background-color: var(--success) !important;
        }

        .bg-warning {
            background-color: var(--warning) !important;
            color: #000 !important;
        }

        .bg-danger {
            background-color: var(--danger) !important;
        }

        .bg-info {
            background-color: #3b82f6 !important;
        }

        /* Pagination */
        .pagination .page-link {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            margin: 0 2px;
        }

        .pagination .page-link:hover {
            background-color: var(--accent) !important;
            border-color: var(--accent) !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent) !important;
            border-color: var(--accent) !important;
            color: white !important;
        }

        /* Alerts */
        .alert {
            border: none !important;
            border-radius: 12px !important;
            border-left: 4px solid transparent !important;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.9), rgba(34, 197, 94, 0.7)) !important;
            color: white !important;
            border-left-color: var(--success) !important;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(239, 68, 68, 0.7)) !important;
            color: white !important;
            border-left-color: var(--danger) !important;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.9), rgba(234, 179, 8, 0.7)) !important;
            color: #000 !important;
            border-left-color: var(--warning) !important;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.9), rgba(59, 130, 246, 0.7)) !important;
            color: white !important;
            border-left-color: #3b82f6 !important;
        }

        /* Modals */
        .modal-content {
            background-color: var(--bg-secondary) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 16px !important;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color) !important;
        }

        /* List group */
        .list-group-item {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .list-group-item:hover {
            background-color: rgba(126, 87, 194, 0.1) !important;
        }

        /* Progress bars */
        .progress {
            background-color: rgba(0, 0, 0, 0.3) !important;
            border-radius: 10px !important;
        }

        .progress-bar {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover)) !important;
            border-radius: 10px !important;
        }

        /* Text colors */
        .text-muted {
            color: var(--text-secondary) !important;
        }

        .text-primary {
            color: var(--accent) !important;
        }

        .text-success {
            color: var(--success) !important;
        }

        .text-warning {
            color: var(--warning) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        /* Background colors */
        .bg-dark {
            background-color: var(--bg-primary) !important;
        }

        .bg-light {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Border colors */
        .border {
            border-color: var(--border-color) !important;
        }

        .border-top {
            border-top-color: var(--border-color) !important;
        }

        .border-bottom {
            border-bottom-color: var(--border-color) !important;
        }

        .border-start {
            border-left-color: var(--border-color) !important;
        }

        .border-end {
            border-right-color: var(--border-color) !important;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-hover);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                    <strong style="font-size: 1.5rem; background: linear-gradient(135deg, var(--accent), var(--accent-hover)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Arku<span style="color: var(--text-primary);">Pro</span>
                    </strong>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            <!-- Menú para invitados -->
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
                            <!-- Menú para usuarios autenticados -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('projects.index') }}">
                                    <i class="fas fa-project-diagram me-1"></i>Proyectos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('time-trackings.index') }}">
                                    <i class="fas fa-clock me-1"></i>Tiempo
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reports.index') }}">
                                    <i class="fas fa-chart-bar me-1"></i>Reportes
                                </a>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tasks.index') }}">
                                    <i class="fas fa-tasks me-2"></i>Tareas
                                </a>
                            </li>
                            </li>
                            
                            <!-- Dropdown de usuario -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user me-2"></i>Mi Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-cog me-2"></i>Configuración
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>

    <!-- JavaScript DE Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Efectos dinámicos adicionales
        document.addEventListener('DOMContentLoaded', function() {
            // Añadir efecto de carga suave
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
            
            // Efecto para dropdowns
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('show.bs.dropdown', function () {
                    this.querySelector('.dropdown-menu').style.opacity = '0';
                    this.querySelector('.dropdown-menu').style.transform = 'translateY(-10px)';
                });
                
                dropdown.addEventListener('shown.bs.dropdown', function () {
                    this.querySelector('.dropdown-menu').style.opacity = '1';
                    this.querySelector('.dropdown-menu').style.transform = 'translateY(0)';
                    this.querySelector('.dropdown-menu').style.transition = 'all 0.3s ease';
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>