@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard de Productividad</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Proyectos Totales</div>
                <div class="stat-card-icon icon-primary">📁</div>
            </div>
            <div class="stat-card-value">{{ $stats['total_projects'] }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Horas esta Semana</div>
                <div class="stat-card-icon icon-success">⏱️</div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['weekly_hours'], 1) }}h</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Productividad</div>
                <div class="stat-card-icon icon-warning">📊</div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['avg_productivity'], 1) }}%</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-title">Proyectos Activos</div>
                <div class="stat-card-icon icon-danger">🚀</div>
            </div>
            <div class="stat-card-value">{{ $stats['active_projects'] }}</div>
        </div>
    </div>
    
    <div class="charts-container">
        <div class="chart-card">
            <div class="chart-header">Actividad Semanal</div>
            <canvas id="weeklyActivityChart" height="250"></canvas>
        </div>
        
        <div class="chart-card">
            <div class="chart-header">Distribución por Tipo</div>
            <canvas id="activityTypeChart" height="250"></canvas>
        </div>
    </div>
    
    <div class="recent-activities">
        <div class="chart-header">Actividad Reciente</div>
        
        @forelse($recentActivities as $activity)
            <div class="activity-item">
                <div class="activity-icon" style="background-color: {{-- tu lógica de colores aquí --}}">
                    {{-- tu lógica de iconos aquí --}}
                </div>
                <div class="activity-content">
                    <div class="activity-title">
                        {{ $activity->project->name }} - {{ $activity->activity_type }}
                    </div>
                    <div class="activity-time">
                        {{ $activity->start_time->format('d/m/Y H:i') }} - 
                        {{ $activity->duration_minutes }} minutos - 
                        {{ $activity->productivity_score }}% productividad
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted py-4">No hay actividad reciente</p>
        @endforelse
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
                data: @json($weeklyChartData),
                backgroundColor: 'rgba(52, 152, 219, 0.8)'
            }]
        },
        options: { /* ... tus opciones ... */ }
    });
    
    // Gráfico de tipos de actividad
    const typeCtx = document.getElementById('activityTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($activityTypeChartData['labels']),
            datasets: [{
                data: @json($activityTypeChartData['data']),
                backgroundColor: ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#95a5a6']
            }]
        }
    });
</script>
@endpush