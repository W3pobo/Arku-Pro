@extends('layouts.app')

@section('title', 'Reporte de Productividad')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Reporte de Productividad Detallado</h1>
                <p class="page-subtitle">Análisis completo de tu rendimiento y métricas de productividad</p>
            </div>
        </div>
    </div>

    <!-- Filtros Mejorados -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-filter me-2"></i>
                        Filtros de Fecha
                    </h3>
                </div>
                <div class="chart-body">
                    <form action="{{ route('reports.productivity') }}" method="GET" class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <label for="start_date" class="form-label text-light">Fecha de inicio</label>
                            <input type="date" name="start_date" id="start_date" 
                                   value="{{ $startDate->format('Y-m-d') }}"
                                   class="form-control form-control-dark">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="end_date" class="form-label text-light">Fecha de fin</label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ $endDate->format('Y-m-d') }}"
                                   class="form-control form-control-dark">
                        </div>
                        <div class="col-lg-4 col-md-12 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary btn-filter">
                                <i class="fas fa-filter me-2"></i>Aplicar Filtros
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

    <!-- Tarjetas de Métricas Principales -->
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="metric-card metric-primary">
                <div class="metric-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="metric-content">
                    <h3 class="metric-value">{{ number_format($hoursThisWeek, 1) }}h</h3>
                    <p class="metric-label">Horas totales</p>
                    <div class="metric-trend">
                        <i class="fas fa-chart-line trend-up"></i>
                        <span>Período seleccionado</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="metric-card metric-success">
                <div class="metric-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="metric-content">
                    <h3 class="metric-value">{{ number_format($averageProductivity, 1) }}%</h3>
                    <p class="metric-label">Productividad promedio</p>
                    <div class="metric-trend">
                        <i class="fas fa-chart-line trend-up"></i>
                        <span>Eficiencia general</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="metric-card metric-info">
                <div class="metric-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="metric-content">
                    <h3 class="metric-value">{{ $workSessions }}</h3>
                    <p class="metric-label">Sesiones de trabajo</p>
                    <div class="metric-trend">
                        <i class="fas fa-chart-line trend-up"></i>
                        <span>Sesiones activas</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="metric-card metric-warning">
                <div class="metric-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="metric-content">
                    <h3 class="metric-value">{{ $activeProjects }}</h3>
                    <p class="metric-label">Proyectos activos</p>
                    <div class="metric-trend">
                        <i class="fas fa-chart-line trend-up"></i>
                        <span>Proyectos en curso</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Tablas -->
    <div class="row mb-5">
        <!-- Productividad por Día de la Semana -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Productividad por Día de la Semana
                    </h3>
                    <div class="table-actions">
                        <span class="table-info">{{ ($weeklyProductivity ?? collect())->count() }} días</span>
                    </div>
                </div>
                <div class="chart-body">
                    @if($weeklyProductivity && $weeklyProductivity->count() > 0)
                        <div class="table-container">
                            <table class="performance-table">
                                <thead>
                                    <tr>
                                        <th class="day-col">Día</th>
                                        <th class="productivity-col">Productividad</th>
                                        <th class="hours-col">Horas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($weeklyProductivity as $day)
                                    <tr class="project-row">
                                        <td class="day-col">
                                            <div class="project-info">
                                                <div class="project-color" style="background-color: #7E57C2"></div>
                                                <div class="project-details">
                                                    <span class="project-name">{{ $day->day_name ?? 'Sin nombre' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="productivity-col">
                                            <div class="productivity-display">
                                                <div class="progress-container">
                                                    <div class="progress-bar 
                                                        @if(($day->productivity ?? 0) >= 70) progress-excellent
                                                        @elseif(($day->productivity ?? 0) >= 50) progress-good
                                                        @else progress-poor
                                                        @endif" 
                                                        style="width: {{ $day->productivity ?? 0 }}%">
                                                        <span class="progress-text">{{ number_format($day->productivity ?? 0, 1) }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hours-col">
                                            <div class="hours-value">
                                                <i class="fas fa-clock me-2"></i>
                                                {{ number_format(($day->total_minutes ?? 0) / 60, 1) }}h
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="chart-empty">
                            <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                            <h4>No hay datos disponibles</h4>
                            <p class="text-muted">No se encontraron registros para los días de la semana</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Distribución por Tipo de Actividad -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-tasks me-2"></i>
                        Distribución por Tipo de Actividad
                    </h3>
                    <div class="table-actions">
                        <span class="table-info">{{ ($activityDistribution ?? collect())->count() }} categorías</span>
                    </div>
                </div>
                <div class="chart-body">
                    @if($activityDistribution && $activityDistribution->count() > 0)
                        <div class="table-container">
                            <table class="performance-table">
                                <thead>
                                    <tr>
                                        <th class="activity-col">Categoría</th>
                                        <th class="hours-col">Horas</th>
                                        <th class="sessions-col">Sesiones</th>
                                        <th class="productivity-col">Productividad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityDistribution as $activity)
                                    <tr class="project-row">
                                        <td class="activity-col">
                                            <div class="project-info">
                                                <div class="project-color" style="background-color: #3b82f6"></div>
                                                <div class="project-details">
                                                    <span class="project-name">{{ $activity['category'] ?? 'Sin categoría' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hours-col">
                                            <div class="hours-value">
                                                <i class="fas fa-clock me-2"></i>
                                                {{ $activity['hours'] ?? 0 }}h
                                            </div>
                                        </td>
                                        <td class="sessions-col">
                                            <div class="sessions-count">
                                                <i class="fas fa-play-circle me-2"></i>
                                                {{ $activity['sessions'] ?? 0 }}
                                            </div>
                                        </td>
                                        <td class="productivity-col">
                                            <div class="productivity-display">
                                                <div class="progress-container">
                                                    <div class="progress-bar 
                                                        @if(($activity['productivity'] ?? 0) >= 70) progress-excellent
                                                        @elseif(($activity['productivity'] ?? 0) >= 50) progress-good
                                                        @else progress-poor
                                                        @endif" 
                                                        style="width: {{ $activity['productivity'] ?? 0 }}%">
                                                        <span class="progress-text">{{ $activity['productivity'] ?? 0 }}%</span>
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
                        <div class="chart-empty">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <h4>Sin datos de actividades</h4>
                            <p class="text-muted">No hay datos de actividades en el período seleccionado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rendimiento por Proyecto -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-table me-2"></i>
                        Rendimiento por Proyecto
                    </h3>
                    <div class="table-actions">
                        <span class="table-info">{{ ($projectPerformance ?? collect())->count() }} proyectos</span>
                    </div>
                </div>
                <div class="chart-body">
                    @if($projectPerformance && $projectPerformance->count() > 0)
                        <div class="table-container">
                            <table class="performance-table">
                                <thead>
                                    <tr>
                                        <th class="project-col">Proyecto</th>
                                        <th class="hours-col">Horas Totales</th>
                                        <th class="productivity-col">Productividad</th>
                                        <th class="sessions-col">Sesiones</th>
                                        <th class="status-col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projectPerformance as $project)
                                    <tr class="project-row">
                                        <td class="project-col">
                                            <div class="project-info">
                                                <div class="project-color" style="background-color: #7E57C2"></div>
                                                <div class="project-details">
                                                    <span class="project-name">{{ $project['name'] ?? 'Sin nombre' }}</span>
                                                @if(isset($project['description']))
                                                <span class="project-description">{{ Str::limit($project['description'], 50) }}</span>
                                                @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hours-col">
                                            <div class="hours-value">
                                                <i class="fas fa-clock me-2"></i>
                                                {{ $project['total_hours'] ?? 0 }}h
                                            </div>
                                        </td>
                                        <td class="productivity-col">
                                            <div class="productivity-display">
                                                <div class="progress-container">
                                                    <div class="progress-bar 
                                                        @if(($project['productivity'] ?? 0) >= 70) progress-excellent
                                                        @elseif(($project['productivity'] ?? 0) >= 50) progress-good
                                                        @else progress-poor
                                                        @endif" 
                                                        style="width: {{ $project['productivity'] ?? 0 }}%">
                                                        <span class="progress-text">{{ $project['productivity'] ?? 0 }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="sessions-col">
                                            <div class="sessions-count">
                                                <i class="fas fa-play-circle me-2"></i>
                                                {{ $project['sessions'] ?? 0 }}
                                            </div>
                                        </td>
                                        <td class="status-col">
                                            @php
                                                $status = $project['status'] ?? 'active';
                                            @endphp
                                            <span class="status-badge status-{{ $status }}">
                                                @switch($status)
                                                    @case('active')
                                                        🚀 Activo
                                                        @break
                                                    @case('completed')
                                                        ✅ Completado
                                                        @break
                                                    @case('paused')
                                                        ⏸️ Pausado
                                                        @break
                                                    @default
                                                        {{ $status }}
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="chart-empty">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <h4>No hay proyectos con registros</h4>
                            <p class="text-muted">Comienza a trabajar en proyectos para ver estadísticas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- CTA para Regresar -->
    <div class="row">
        <div class="col-12">
            <div class="cta-card">
                <div class="cta-content">
                    <div class="cta-icon">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    <div class="cta-text">
                        <h3>¿Necesitas ver el resumen principal?</h3>
                        <p>Regresa al dashboard principal para una vista general de tu productividad.</p>
                    </div>
                    <div class="cta-action">
                        <a href="{{ route('reports.index') }}" class="btn-cta">
                            <i class="fas fa-chart-line me-2"></i>
                            Volver a Reportes Principales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --bg-main: #121826;
    --bg-secondary: #1e293b;
    --bg-dark: #0f172a;
    --accent: #7E57C2;
    --accent-light: #9c7bd4;
    --text-main: #f8fafc;
    --text-light: #e2e8f0;
    --text-muted: #94a3b8;
    --border-color: #334155;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --card-shadow: 0 4px 20px rgba(0,0,0,0.15);
    --card-shadow-hover: 0 8px 30px rgba(126, 87, 194, 0.15);
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

/* === HEADER MEJORADO === */
.page-header {
    text-align: center;
    padding: 2rem 0;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, var(--text-light), var(--accent-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* === TARJETAS DE MÉTRICAS === */
.metric-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow-hover);
}

.metric-primary::before { background: linear-gradient(90deg, var(--accent), var(--accent-light)); }
.metric-success::before { background: linear-gradient(90deg, var(--success), #34d399); }
.metric-info::before { background: linear-gradient(90deg, var(--info), #60a5fa); }
.metric-warning::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.metric-primary .metric-icon { background: rgba(126, 87, 194, 0.2); color: var(--accent); }
.metric-success .metric-icon { background: rgba(16, 185, 129, 0.2); color: var(--success); }
.metric-info .metric-icon { background: rgba(59, 130, 246, 0.2); color: var(--info); }
.metric-warning .metric-icon { background: rgba(245, 158, 11, 0.2); color: var(--warning); }

.metric-content {
    flex: 1;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 0.25rem;
    line-height: 1;
}

.metric-label {
    color: var(--text-muted);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.metric-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.trend-up { color: var(--success); }
.trend-down { color: var(--danger); }

/* === TARJETAS DE GRÁFICOS === */
.chart-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.chart-card:hover {
    box-shadow: var(--card-shadow-hover);
}

.chart-header {
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-light);
    margin: 0;
    display: flex;
    align-items: center;
}

.table-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.table-info {
    font-size: 0.85rem;
    color: var(--text-muted);
    background: rgba(255,255,255,0.05);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
}

.chart-body {
    padding: 1.5rem;
}

.chart-empty {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--text-muted);
}

.chart-empty i {
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* === TABLA DE RENDIMIENTO === */
.table-container {
    overflow-x: auto;
}

.performance-table {
    width: 100%;
    border-collapse: collapse;
}

.performance-table th {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--text-light);
    border-bottom: 2px solid var(--border-color);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.performance-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.project-row {
    transition: background-color 0.3s ease;
}

.project-row:hover {
    background: rgba(126, 87, 194, 0.05);
}

.day-col { width: 25%; }
.activity-col { width: 25%; }
.project-col { width: 25%; }
.hours-col { width: 15%; }
.productivity-col { width: 30%; }
.sessions-col { width: 15%; }
.status-col { width: 15%; }

.project-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.project-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.project-details {
    display: flex;
    flex-direction: column;
}

.project-name {
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.project-description {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.hours-value, .sessions-count {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: var(--text-light);
}

.progress-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    height: 24px;
    overflow: hidden;
    position: relative;
    margin-bottom: 0.5rem;
}

.progress-bar {
    height: 100%;
    border-radius: 10px;
    position: relative;
    transition: width 0.5s ease;
}

.progress-excellent { background: linear-gradient(90deg, var(--success), #34d399); }
.progress-good { background: linear-gradient(90deg, var(--warning), #fbbf24); }
.progress-poor { background: linear-gradient(90deg, var(--danger), #f87171); }

.progress-text {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.productivity-details {
    display: flex;
    gap: 1rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border-color: rgba(16, 185, 129, 0.3);
}

.status-completed {
    background: rgba(126, 87, 194, 0.1);
    color: var(--accent);
    border-color: rgba(126, 87, 194, 0.3);
}

.status-paused {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border-color: rgba(245, 158, 11, 0.3);
}

/* === FORMULARIOS === */
.form-control-dark {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-main);
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.form-control-dark:focus {
    background-color: rgba(255, 255, 255, 0.08);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25);
}

.form-label {
    color: var(--text-light);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.btn-filter {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.4);
}

/* === CTA CARD === */
.cta-card {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    border-radius: 16px;
    padding: 2rem;
    color: white;
}

.cta-content {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.cta-icon {
    font-size: 3rem;
    opacity: 0.9;
}

.cta-text {
    flex: 1;
}

.cta-text h3 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.cta-text p {
    margin: 0;
    opacity: 0.9;
}

.btn-cta {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    backdrop-filter: blur(10px);
}

.btn-cta:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .metric-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .cta-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .performance-table th,
    .performance-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .project-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .project-color {
        width: 100%;
        height: 4px;
        border-radius: 2px;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .chart-body {
        padding: 1rem;
    }
    
    .productivity-details {
        flex-direction: column;
        gap: 0.25rem;
    }
}


</style>
@endsection