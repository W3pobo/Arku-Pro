@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Dashboard de Productividad</h1>
    
    <!-- Estadísticas Principales -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">📁</div>
                    <h3 class="card-title text-primary">{{ $stats['total_projects'] }}</h3>
                    <p class="card-text text-muted">Proyectos Totales</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">⏱️</div>
                    <h3 class="card-title text-success">{{ number_format($stats['weekly_hours'], 1) }}h</h3>
                    <p class="card-text text-muted">Horas esta Semana</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">📊</div>
                    <h3 class="card-title text-warning">{{ number_format($stats['avg_productivity'], 1) }}%</h3>
                    <p class="card-text text-muted">Productividad</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">🚀</div>
                    <h3 class="card-title text-danger">{{ $stats['active_projects'] }}</h3>
                    <p class="card-text text-muted">Proyectos Activos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas de Productividad Avanzadas y Recomendaciones -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Análisis de Productividad</h5>
                </div>
                <div class="card-body">
                    @foreach([
                        'efficiency_score' => ['Eficiencia General', 'success'],
                        'focus_ratio' => ['Ratio de Concentración', 'primary'], 
                        'consistency_score' => ['Consistencia', 'info']
                    ] as $key => [$label, $color])
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">{{ $label }}</span>
                            <span class="small text-muted">{{ $productivityMetrics[$key] ?? 0 }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $color }}" 
                                 style="width: {{ $productivityMetrics[$key] ?? 0 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-4 text-center">
                            <div class="h4 text-dark">{{ $productivityMetrics['total_time_hours'] ?? 0 }}</div>
                            <small class="text-muted">Horas Totales</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="h4 text-success">{{ $productivityMetrics['productive_time_hours'] ?? 0 }}</div>
                            <small class="text-muted">Horas Productivas</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="h4 text-primary">{{ $productivityMetrics['tasks_completed'] ?? 0 }}</div>
                            <small class="text-muted">Tareas Completadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Recomendaciones</h5>
                </div>
                <div class="card-body">
                    @forelse($recommendations ?? [] as $recommendation)
                    <div class="alert alert-info d-flex align-items-start mb-2 py-2">
                        <i class="fas fa-lightbulb me-2 mt-1"></i>
                        <div class="small">{{ $recommendation }}</div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p class="mb-0">No hay recomendaciones disponibles</p>
                        <small>¡Sigue trabajando!</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución por Categorías -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Distribución por Categorías</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($activityStats ?? [] as $activity)
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card border h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $activity->activity_type }}</h6>
                                <span class="badge bg-primary">{{ round($activity->total_minutes / 60, 1) }}h</span>
                            </div>
                            @php
                                $totalHours = $activityStats->sum('total_minutes') / 60;
                                $activityHours = $activity->total_minutes / 60;
                                $percentage = $totalHours > 0 ? ($activityHours / $totalHours) * 100 : 0;
                            @endphp
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>{{ round($percentage, 1) }}%</span>
                                <span>Prod: {{ round($activity->avg_productivity, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay datos de categorías disponibles</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Actividad Semanal</h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyActivityChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Distribución por Tipo</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityTypeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actividad Reciente -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Actividad Reciente</h5>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                @forelse($recentActivities as $activity)
                <div class="list-group-item d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
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
                                <h6 class="mb-1">{{ $activity->project->name ?? 'Sin proyecto' }} - {{ $activity->activity_type }}</h6>
                                <p class="mb-1 small text-muted">
                                    {{ $activity->start_time->format('d/m/Y H:i') }} • 
                                    {{ $activity->duration_minutes }} minutos • 
                                    <span class="fw-bold" style="color: {{ $activity->getProductivityColorAttribute() }}">
                                        {{ $activity->productivity_score }}% productividad
                                    </span>
                                </p>
                            </div>
                            @if($activity->activityCategory)
                            <span class="badge" style="background-color: {{ $activity->activityCategory->color }}20; color: {{ $activity->activityCategory->color }};">
                                {{ $activity->activityCategory->icon }} {{ $activity->activityCategory->name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-history fa-2x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No hay actividad reciente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gráfico de actividad semanal
    const weeklyCtx = document.getElementById('weeklyActivityChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Horas de trabajo',
                data: @json($weeklyChartData ?? []),
                backgroundColor: 'rgba(52, 152, 219, 0.8)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 1
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
                backgroundColor: ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#95a5a6', '#9b59b6', '#1abc9c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush