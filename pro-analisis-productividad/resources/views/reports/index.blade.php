@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Reportes de Productividad</h1>
                <p class="page-subtitle">Analiza tu rendimiento y optimiza tu tiempo de trabajo</p>
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
                    <h3 class="metric-value">{{ number_format(($weeklyStats ? $weeklyStats->sum('total_minutes') : 0) / 60, 1) }}h</h3>
                    <p class="metric-label">Horas esta semana</p>
                    <div class="metric-trend">
                        <i class="fas fa-chart-line trend-up"></i>
                        <span>Tendencia semanal</span>
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
                    <h3 class="metric-value">{{ number_format($weeklyStats ? $weeklyStats->avg('avg_productivity') : 0, 1) }}%</h3>
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
                    <h3 class="metric-value">{{ $weeklyStats ? $weeklyStats->sum('total_sessions') : 0 }}</h3>
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
                    <h3 class="metric-value">{{ $projectStats ? $projectStats->count() : 0 }}</h3>
                    <p class="metric-label">Proyectos activos</p>
                    <div class="metric-trend">
                        <i class="fas fa-chart-line trend-up"></i>
                        <span>Proyectos en curso</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Gráficos -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Productividad por Día de la Semana
                    </h3>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color bar-color"></div>
                            <span>Horas de trabajo</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color line-color"></div>
                            <span>Productividad (%)</span>
                        </div>
                    </div>
                </div>
                <div class="chart-body">
                    @if($weeklyStats && $weeklyStats->count() > 0)
                    <canvas id="weeklyChart" height="280"></canvas>
                    @else
                    <div class="chart-empty">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <h4>No hay datos disponibles</h4>
                        <p class="text-muted">Comienza a registrar tu tiempo para ver estadísticas</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        Distribución por Actividad
                    </h3>
                </div>
                <div class="chart-body">
                    @if($activityStats && $activityStats->count() > 0)
                    <canvas id="activityChart" height="280"></canvas>
                    @else
                    <div class="chart-empty">
                        <i class="fas fa-chart-pie fa-3x mb-3"></i>
                        <h4>Sin datos de actividades</h4>
                        <p class="text-muted">Registra diferentes tipos de actividades</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Rendimiento por Proyecto -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-table me-2"></i>
                        Rendimiento por Proyecto
                    </h3>
                    <div class="table-actions">
                        <span class="table-info">{{ ($projectStats ?? collect())->count() }} proyectos</span>
                    </div>
                </div>
                <div class="chart-body">
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
                                @forelse($projectStats ?? [] as $project)
                                <tr class="project-row">
                                    <td class="project-col">
                                        <div class="project-info">
                                            <div class="project-color" style="background-color: {{ $project->project_color ?? '#7E57C2' }}"></div>
                                            <div class="project-details">
                                                <span class="project-name">{{ $project->project_name ?? 'Sin nombre' }}</span>
                                                @if($project->project_description)
                                                <span class="project-description">{{ Str::limit($project->project_description, 50) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hours-col">
                                        <div class="hours-value">
                                            <i class="fas fa-clock me-2"></i>
                                            {{ number_format(($project->total_minutes ?? 0) / 60, 1) }}h
                                        </div>
                                    </td>
                                    <td class="productivity-col">
                                        @php
                                            $avgProductivity = (($project->avg_focus ?? 0) + ($project->avg_energy ?? 0)) / 2;
                                        @endphp
                                        <div class="productivity-display">
                                            <div class="progress-container">
                                                <div class="progress-bar 
                                                    @if($avgProductivity >= 70) progress-excellent
                                                    @elseif($avgProductivity >= 50) progress-good
                                                    @else progress-poor
                                                    @endif" 
                                                    style="width: {{ $avgProductivity }}%">
                                                    <span class="progress-text">{{ number_format($avgProductivity, 1) }}%</span>
                                                </div>
                                            </div>
                                            <div class="productivity-details">
                                                <small>Foco: {{ number_format($project->avg_focus ?? 0, 1) }}%</small>
                                                <small>Energía: {{ number_format($project->avg_energy ?? 0, 1) }}%</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="sessions-col">
                                        <div class="sessions-count">
                                            <i class="fas fa-play-circle me-2"></i>
                                            {{ $project->session_count ?? 0 }}
                                        </div>
                                    </td>
                                    <td class="status-col">
                                        @php
                                            $status = $project->project_status ?? 'active';
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
                                @empty
                                <tr class="empty-row">
                                    <td colspan="5">
                                        <div class="empty-table">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <h4>No hay proyectos con registros</h4>
                                            <p class="text-muted">Comienza a trabajar en proyectos para ver estadísticas</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA para Reporte Detallado -->
    <div class="row">
        <div class="col-12">
            <div class="cta-card">
                <div class="cta-content">
                    <div class="cta-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="cta-text">
                        <h3>¿Necesitas análisis más detallados?</h3>
                        <p>Explora el reporte completo de productividad con métricas avanzadas y tendencias históricas.</p>
                    </div>
                    <div class="cta-action">
                        <a href="{{ route('reports.productivity') }}" class="btn-cta">
                            <i class="fas fa-chart-line me-2"></i>
                            Ver Reporte Completo
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
    justify-content: between;
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

.chart-legend {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.bar-color { background-color: var(--accent); }
.line-color { background-color: var(--success); }

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

.empty-row td {
    padding: 3rem;
    text-align: center;
}

.empty-table {
    color: var(--text-muted);
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración global de Chart.js para el tema oscuro
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#334155';
    Chart.defaults.font.family = 'system-ui, -apple-system, sans-serif';

    // Gráfico semanal
    @if($weeklyStats && $weeklyStats->count() > 0)
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyStats->pluck('day_name')) !!},
            datasets: [{
                label: 'Horas de trabajo',
                data: {!! json_encode($weeklyStats->pluck('total_minutes')->map(function($min) { return ($min ?? 0) / 60; })) !!},
                backgroundColor: 'rgba(126, 87, 194, 0.8)',
                borderRadius: 6,
                borderWidth: 0,
            }, {
                label: 'Productividad (%)',
                data: {!! json_encode($weeklyStats->pluck('avg_productivity')) !!},
                type: 'line',
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { 
                        display: true, 
                        text: 'Horas',
                        color: '#94a3b8',
                        font: { weight: '600' }
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    max: 100,
                    title: { 
                        display: true, 
                        text: 'Productividad %',
                        color: '#94a3b8',
                        font: { weight: '600' }
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#94a3b8',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.95)',
                    titleColor: '#f8fafc',
                    bodyColor: '#e2e8f0',
                    borderColor: '#334155',
                    borderWidth: 1
                }
            }
        }
    });
    @endif

    // Gráfico de actividades
    @if($activityStats && $activityStats->count() > 0)
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($activityStats->pluck('activity_type')) !!},
            datasets: [{
                data: {!! json_encode($activityStats->pluck('total_minutes')->map(function($min) { return ($min ?? 0) / 60; })) !!},
                backgroundColor: [
                    '#7E57C2', '#3b82f6', '#10b981', '#f59e0b',
                    '#ef4444', '#8b5cf6', '#06b6d4', '#64748b'
                ],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        boxWidth: 12,
                        color: '#94a3b8',
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.95)',
                    titleColor: '#f8fafc',
                    bodyColor: '#e2e8f0',
                    borderColor: '#334155',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value}h (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
    @endif
</script>
@endsection
@endsection