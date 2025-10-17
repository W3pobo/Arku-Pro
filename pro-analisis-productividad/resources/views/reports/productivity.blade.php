@extends('layouts.app')

@section('title', 'Reporte de Productividad')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4 text-main">Reporte de Productividad</h1>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-body-custom">
                    <h5 class="card-title-custom mb-3">Filtrar por fecha</h5>
                    <form action="{{ route('reports.productivity') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label text-main">Fecha de inicio</label>
                            <input type="date" name="start_date" id="start_date" 
                                   value="{{ $startDate->format('Y-m-d') }}"
                                   class="form-control bg-gray-700 border-gray-600 text-white">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label text-main">Fecha de fin</label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ $endDate->format('Y-m-d') }}"
                                   class="form-control bg-gray-700 border-gray-600 text-white">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-2"></i>Filtrar
                            </button>
                            <a href="{{ route('reports.productivity') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-custom bg-accent text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Horas esta semana</h5>
                    <h2 class="text-white">{{ number_format($hoursThisWeek, 1) }}h</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom bg-success text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Productividad promedio</h5>
                    <h2 class="text-white">{{ number_format($averageProductivity, 1) }}%</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom bg-info text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Sesiones de trabajo</h5>
                    <h2 class="text-white">{{ $workSessions }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom bg-warning text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Proyectos activos</h5>
                    <h2 class="text-white">{{ $activeProjects }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row">
        <!-- Productividad por Día de la Semana -->
        <div class="col-lg-6">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Productividad por Día de la Semana</h5>
                </div>
                <div class="card-body-custom">
                    @if($weeklyProductivity->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-dark table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-main">Día</th>
                                        <th class="text-main">Productividad</th>
                                        <th class="text-main">Horas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($weeklyProductivity as $day)
                                    <tr class="table-row-custom">
                                        <td class="text-main">{{ $day->day_name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress-custom me-2 flex-grow-1">
                                                    <div class="progress-bar-custom bg-success" 
                                                         style="width: {{ $day->productivity ?? 0 }}%">
                                                        <span class="progress-text">{{ number_format($day->productivity ?? 0, 1) }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-accent">{{ number_format(($day->total_minutes ?? 0) / 60, 1) }}h</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-secondary text-center mb-0">No hay datos disponibles para esta semana.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Distribución por Tipo de Actividad -->
        <div class="col-lg-6">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Distribución por Tipo de Actividad</h5>
                </div>
                <div class="card-body-custom">
                    @if($activityDistribution->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-dark table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-main">Categoría</th>
                                        <th class="text-main">Horas</th>
                                        <th class="text-main">Sesiones</th>
                                        <th class="text-main">Productividad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityDistribution as $activity)
                                    <tr class="table-row-custom">
                                        <td class="text-main">{{ $activity['category'] }}</td>
                                        <td class="text-accent">{{ $activity['hours'] }}h</td>
                                        <td class="text-secondary">{{ $activity['sessions'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress-custom me-2 flex-grow-1">
                                                    <div class="progress-bar-custom bg-info" 
                                                         style="width: {{ $activity['productivity'] }}%">
                                                        <span class="progress-text">{{ $activity['productivity'] }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-secondary text-center mb-0">No hay datos de actividades en los últimos 30 días.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rendimiento por Proyecto -->
    <div class="row">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Rendimiento por Proyecto</h5>
                </div>
                <div class="card-body-custom">
                    @if($projectPerformance->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-main">Proyecto</th>
                                        <th class="text-main">Horas Totales</th>
                                        <th class="text-main">Productividad</th>
                                        <th class="text-main">Sesiones</th>
                                        <th class="text-main">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projectPerformance as $project)
                                    <tr class="table-row-custom">
                                        <td class="text-main">{{ $project['name'] }}</td>
                                        <td class="text-accent">{{ $project['total_hours'] }}h</td>
                                        <td>
                                            <div class="progress-custom">
                                                <div class="progress-bar-custom 
                                                    @if($project['productivity'] >= 70) bg-success
                                                    @elseif($project['productivity'] >= 50) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                    style="width: {{ $project['productivity'] }}%">
                                                    <span class="progress-text">{{ $project['productivity'] }}%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-secondary">{{ $project['sessions'] }}</td>
                                        <td>
                                            <span class="badge-custom 
                                                @if($project['status'] == 'active') bg-success
                                                @elseif($project['status'] == 'completed') bg-info
                                                @else bg-secondary
                                                @endif">
                                                {{ $project['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                <i class="fas fa-folder-open fa-2x mb-3 text-accent"></i>
                                <p>No hay proyectos con registros de tiempo.</p>
                            </td>
                        </tr>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Enlace de regreso -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-body-custom text-center">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a Reportes Principales
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --bg-main: #121826;
    --bg-secondary: #2A3241;
    --accent: #7E57C2;
    --text-main: #F0F2F5;
    --text-secondary: #A9B4C7;
    --hover-accent: #6a4da2;
    --border-color: #3a4458;
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

.text-main { color: var(--text-main); }
.text-secondary { color: var(--text-secondary); }
.text-accent { color: var(--accent); }
.bg-accent { background-color: var(--accent); }

.card-custom {
    background-color: var(--bg-secondary);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), transparent);
}

.card-custom:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(126, 87, 194, 0.15);
}

.card-header-custom {
    background-color: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
}

.card-body-custom {
    padding: 1.5rem;
    color: var(--text-main);
}

.card-title-custom {
    color: var(--text-main);
    font-weight: 600;
    margin-bottom: 0;
}

.table-dark {
    background-color: transparent;
    color: var(--text-main);
}

.table-row-custom {
    transition: background-color 0.3s ease;
}

.table-row-custom:hover {
    background-color: rgba(126, 87, 194, 0.1);
}

.progress-custom {
    height: 24px;
    background-color: rgba(255,255,255,0.1);
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}

.progress-bar-custom {
    height: 100%;
    border-radius: 12px;
    position: relative;
    transition: width 0.5s ease;
}

.progress-text {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.8em;
    font-weight: 600;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.badge-custom {
    background-color: rgba(255,255,255,0.1);
    color: var(--text-secondary);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    border: 1px solid rgba(255,255,255,0.1);
}

/* Colores para las tarjetas de estadísticas */
.bg-success { background: linear-gradient(135deg, #2ecc71, #27ae60) !important; }
.bg-info { background: linear-gradient(135deg, #3498db, #2980b9) !important; }
.bg-warning { background: linear-gradient(135deg, #f39c12, #e67e22) !important; }
.bg-danger { background: linear-gradient(135deg, #e74c3c, #c0392b) !important; }

/* Mejoras para las tarjetas de estadísticas */
.card-custom.bg-accent,
.card-custom.bg-success,
.card-custom.bg-info,
.card-custom.bg-warning {
    border: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.card-custom.bg-accent:hover,
.card-custom.bg-success:hover,
.card-custom.bg-info:hover,
.card-custom.bg-warning:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 30px rgba(0,0,0,0.4);
}

.bg-gray-700 { background-color: #374151 !important; }
.border-gray-600 { border-color: #4B5563 !important; }
</style>
@endsection