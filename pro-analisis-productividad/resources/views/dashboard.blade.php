@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-main">Dashboard de Productividad</h1>
    
    <!-- Estadísticas Principales -->
    <div class="row mb-4">
        @php
            $statsCards = [
                ['icon' => '📁', 'value' => $stats['total_projects'], 'label' => 'Proyectos Totales', 'color' => 'accent'],
                ['icon' => '⏱️', 'value' => number_format($stats['weekly_hours'], 1).'h', 'label' => 'Horas esta Semana', 'color' => 'success'],
                ['icon' => '📊', 'value' => number_format($stats['avg_productivity'], 1).'%', 'label' => 'Productividad', 'color' => 'warning'],
                ['icon' => '🚀', 'value' => $stats['active_projects'], 'label' => 'Proyectos Activos', 'color' => 'danger'],
            ];
        @endphp
        
        @foreach($statsCards as $card)
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card-custom text-center h-100">
                <div class="card-body-custom">
                    <div class="fs-2 mb-2">{{ $card['icon'] }}</div>
                    <h3 class="card-title-custom text-{{ $card['color'] }}">{{ $card['value'] }}</h3>
                    <p class="card-text-custom">{{ $card['label'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Métricas de Productividad Avanzadas y Recomendaciones -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Análisis de Productividad</h5>
                </div>
                <div class="card-body-custom">
                    @foreach([
                        'efficiency_score' => ['Eficiencia General', 'success'],
                        'focus_ratio' => ['Ratio de Concentración', 'accent'], 
                        'consistency_score' => ['Consistencia', 'info']
                    ] as $key => [$label, $color])
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-secondary">{{ $label }}</span>
                            <span class="small text-secondary">{{ $productivityMetrics[$key] ?? 0 }}%</span>
                        </div>
                        <div class="progress-custom" style="height: 8px;">
                            <div class="progress-bar-custom bg-{{ $color }}" 
                                 style="width: {{ $productivityMetrics[$key] ?? 0 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="row mt-3 pt-3 border-top border-gray-600">
                        <div class="col-4 text-center">
                            <div class="h4 text-main">{{ $productivityMetrics['total_time_hours'] ?? 0 }}</div>
                            <small class="text-secondary">Horas Totales</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="h4 text-success">{{ $productivityMetrics['productive_time_hours'] ?? 0 }}</div>
                            <small class="text-secondary">Horas Productivas</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="h4 text-accent">{{ $productivityMetrics['tasks_completed'] ?? 0 }}</div>
                            <small class="text-secondary">Tareas Completadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Recomendaciones</h5>
                </div>
                <div class="card-body-custom">
                    @forelse($recommendations ?? [] as $recommendation)
                    <div class="alert-custom alert-info d-flex align-items-start mb-2 py-2">
                        <i class="fas fa-lightbulb me-2 mt-1 text-accent"></i>
                        <div class="small text-main">{{ $recommendation }}</div>
                    </div>
                    @empty
                    <div class="text-center text-secondary py-3">
                        <i class="fas fa-info-circle fa-2x mb-2 text-accent"></i>
                        <p class="mb-0 text-main">No hay recomendaciones disponibles</p>
                        <small class="text-secondary">¡Sigue trabajando!</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución por Categorías -->
    <div class="card-custom mb-4">
        <div class="card-header-custom">
            <h5 class="card-title-custom mb-0">Distribución por Categorías</h5>
        </div>
        <div class="card-body-custom">
            <div class="row">
                @forelse($activityStats ?? [] as $activity)
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card-custom border-custom h-100">
                        <div class="card-body-custom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title-custom mb-0">{{ $activity->activity_type }}</h6>
                                <span class="badge-custom bg-accent">{{ round($activity->total_minutes / 60, 1) }}h</span>
                            </div>
                            @php
                                $totalHours = $activityStats->sum('total_minutes') / 60;
                                $activityHours = $activity->total_minutes / 60;
                                $percentage = $totalHours > 0 ? ($activityHours / $totalHours) * 100 : 0;
                            @endphp
                            <div class="progress-custom mb-2" style="height: 6px;">
                                <div class="progress-bar-custom bg-accent" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-secondary">
                                <span>{{ round($percentage, 1) }}%</span>
                                <span>Prod: {{ round($activity->avg_productivity, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <i class="fas fa-chart-pie fa-3x text-accent mb-3"></i>
                    <p class="text-secondary">No hay datos de categorías disponibles</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Actividad Semanal</h5>
                </div>
                <div class="card-body-custom">
                    <canvas id="weeklyActivityChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Distribución por Tipo</h5>
                </div>
                <div class="card-body-custom">
                    <canvas id="activityTypeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actividad Reciente -->
    <div class="card-custom">
        <div class="card-header-custom">
            <h5 class="card-title-custom mb-0">Actividad Reciente</h5>
        </div>
        <div class="card-body-custom">
            <div class="list-group-custom list-group-flush">
                @forelse($recentActivities as $activity)
                <div class="list-group-item-custom d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-accent text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            @if($activity->activityCategory && $activity->activityCategory->icon)
                                <span>{{ $activity->activityCategory->icon }}</span>
                            @else
                                <i class="fas fa-clock"></i>
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 text-main">{{ $activity->project->name ?? 'Sin proyecto' }} - {{ $activity->activity_type }}</h6>
                                <p class="mb-1 small text-secondary">
                                    {{ $activity->start_time->format('d/m/Y H:i') }} • 
                                    {{ $activity->duration_minutes }} minutos • 
                                    <span class="fw-bold" style="color: {{ $activity->getProductivityColorAttribute() }}">
                                        {{ $activity->productivity_score }}% productividad
                                    </span>
                                </p>
                            </div>
                            @if($activity->activityCategory)
                            <span class="badge-custom" style="background-color: {{ $activity->activityCategory->color }}20; color: {{ $activity->activityCategory->color }};">
                                {{ $activity->activityCategory->icon }} {{ $activity->activityCategory->name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-history fa-2x text-accent mb-3"></i>
                    <p class="text-secondary mb-0">No hay actividad reciente</p>
                </div>
                @endforelse
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
.border-gray-600 { border-color: var(--border-color); }

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

.card-text-custom {
    color: var(--text-secondary);
    margin-bottom: 0;
}

.border-custom {
    border: 1px solid var(--border-color) !important;
}

.progress-custom {
    background-color: rgba(255,255,255,0.1);
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar-custom {
    border-radius: 10px;
    transition: width 0.5s ease;
}

.alert-custom {
    background-color: rgba(126, 87, 194, 0.1);
    border: 1px solid rgba(126, 87, 194, 0.3);
    border-radius: 8px;
    color: var(--text-main);
}

.badge-custom {
    background-color: rgba(126, 87, 194, 0.2);
    color: var(--accent);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    border: 1px solid rgba(126, 87, 194, 0.3);
    font-weight: 500;
}

.list-group-custom {
    background-color: transparent;
}

.list-group-item-custom {
    background-color: transparent;
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 0;
    transition: background-color 0.3s ease;
}

.list-group-item-custom:hover {
    background-color: rgba(126, 87, 194, 0.05);
}

.list-group-item-custom:last-child {
    border-bottom: none;
}

/* Colores para los estados */
.text-success { color: #2ecc71 !important; }
.bg-success { background-color: #2ecc71 !important; }

.text-warning { color: #f39c12 !important; }
.bg-warning { background-color: #f39c12 !important; }

.text-danger { color: #e74c3c !important; }
.bg-danger { background-color: #e74c3c !important; }

.text-info { color: #3498db !important; }
.bg-info { background-color: #3498db !important; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración global de Chart.js para el tema oscuro
    Chart.defaults.color = '#A9B4C7';
    Chart.defaults.borderColor = '#3a4458';

    // Gráfico de actividad semanal
    const weeklyCtx = document.getElementById('weeklyActivityChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Horas de trabajo',
                data: @json($weeklyChartData ?? []),
                backgroundColor: 'rgba(126, 87, 194, 0.8)',
                borderColor: 'rgba(126, 87, 194, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Horas'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Gráfico de tipos de actividad
    const typeCtx = document.getElementById('activityTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($activityTypeChartData['labels'] ?? []),
            datasets: [{
                data: @json($activityTypeChartData['data'] ?? []),
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
</script>
@endpush