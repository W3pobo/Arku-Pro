@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4 text-main">Reportes de Productividad</h1>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-custom bg-accent text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Horas esta semana</h5>
                    <h2 class="text-white">{{ number_format(($weeklyStats ? $weeklyStats->sum('total_minutes') : 0) / 60, 1) }}h</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom bg-success text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Productividad promedio</h5>
                    <h2 class="text-white">{{ number_format($weeklyStats ? $weeklyStats->avg('avg_productivity') : 0, 1) }}%</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom bg-info text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Sesiones de trabajo</h5>
                    <h2 class="text-white">{{ $weeklyStats ? $weeklyStats->sum('total_sessions') : 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom bg-warning text-white">
                <div class="card-body-custom">
                    <h5 class="card-title-custom">Proyectos activos</h5>
                    <h2 class="text-white">{{ $projectStats ? $projectStats->count() : 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row">
        <div class="col-md-8">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Productividad por Día de la Semana</h5>
                </div>
                <div class="card-body-custom">
                    @if($weeklyStats && $weeklyStats->count() > 0)
                    <canvas id="weeklyChart" height="250"></canvas>
                    @else
                    <p class="text-secondary">No hay datos disponibles para esta semana.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Distribución por Tipo de Actividad</h5>
                </div>
                <div class="card-body-custom">
                    @if($activityStats && $activityStats->count() > 0)
                    <canvas id="activityChart" height="250"></canvas>
                    @else
                    <p class="text-secondary">No hay datos de actividades disponibles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Proyectos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Rendimiento por Proyecto</h5>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th class="text-main">Proyecto</th>
                                    <th class="text-main">Horas Totales</th>
                                    <th class="text-main">Productividad</th>
                                    <th class="text-main">Sesiones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projectStats ?? [] as $project)
                                <tr class="table-row-custom">
                                    <td class="text-main">{{ $project->project_name ?? 'Sin nombre' }}</td>
                                    <td class="text-accent">{{ number_format(($project->total_minutes ?? 0) / 60, 1) }}h</td>
                                    <td>
                                        <div class="progress-custom">
                                            @php
                                                $avgProductivity = (($project->avg_focus ?? 0) + ($project->avg_energy ?? 0)) / 2;
                                            @endphp
                                            <div class="progress-bar-custom 
                                                @if($avgProductivity >= 70) bg-success
                                                @elseif($avgProductivity >= 50) bg-warning
                                                @else bg-danger
                                                @endif" 
                                                style="width: {{ $avgProductivity }}%">
                                                <span class="progress-text">{{ number_format($avgProductivity, 1) }}%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary">{{ $project->session_count ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary py-4">
                                        <i class="fas fa-folder-open fa-2x mb-3 text-accent"></i>
                                        <p>No hay proyectos con registros de tiempo.</p>
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

    <!-- Enlace al reporte detallado de productividad -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-body-custom text-center">
                    <h5 class="card-title-custom mb-3">¿Necesitas más detalles?</h5>
                    <p class="text-secondary mb-4">Consulta el reporte completo de productividad con análisis detallados.</p>
                    <a href="{{ route('reports.productivity') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line me-2"></i>Ver Reporte Completo de Productividad
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
</style>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración global de Chart.js para el tema oscuro
    Chart.defaults.color = '#A9B4C7';
    Chart.defaults.borderColor = '#3a4458';

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
                borderRadius: 4,
            }, {
                label: 'Productividad (%)',
                data: {!! json_encode($weeklyStats->pluck('avg_productivity')) !!},
                type: 'line',
                borderColor: '#2ecc71',
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { 
                        display: true, 
                        text: 'Horas',
                        color: '#A9B4C7'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    max: 100,
                    title: { 
                        display: true, 
                        text: 'Productividad %',
                        color: '#A9B4C7'
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#A9B4C7'
                    }
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
                    '#7E57C2', '#3498db', '#2ecc71', '#f39c12', 
                    '#e74c3c', '#9b59b6', '#1abc9c', '#34495e'
                ],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        boxWidth: 12,
                        color: '#A9B4C7'
                    }
                }
            },
            cutout: '60%'
        }
    });
    @endif
</script>
@endsection
@endsection