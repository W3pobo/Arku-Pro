@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Reportes de Productividad</h1>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Horas esta semana</h5>
                    <h2>{{ number_format($weeklyStats->sum('total_minutes') / 60, 1) }}h</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Productividad promedio</h5>
                    <h2>{{ number_format($weeklyStats->avg('avg_productivity'), 1) }}%</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Sesiones de trabajo</h5>
                    <h2>{{ $weeklyStats->sum('total_sessions') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Proyectos activos</h5>
                    <h2>{{ $projectStats->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Productividad por Día de la Semana</h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Distribución por Tipo de Actividad</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Proyectos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Rendimiento por Proyecto</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Horas Totales</th>
                                    <th>Productividad</th>
                                    <th>Sesiones</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projectStats as $project)
                                <tr>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ number_format($project->timeTrackings->sum('duration_minutes') / 60, 1) }}h</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar 
                                                @if($project->timeTrackings->avg('productivity_score') >= 80) bg-success
                                                @elseif($project->timeTrackings->avg('productivity_score') >= 50) bg-warning
                                                @else bg-danger
                                                @endif" 
                                                style="width: {{ $project->timeTrackings->avg('productivity_score') }}%">
                                                {{ number_format($project->timeTrackings->avg('productivity_score'), 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $project->timeTrackings->count() }}</td>
                                    <td>
                                        <span class="badge bg-{{ $project->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $project->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico semanal
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyStats->pluck('day_name')) !!},
            datasets: [{
                label: 'Horas de trabajo',
                data: {!! json_encode($weeklyStats->pluck('total_minutes')->map(function($min) { return $min / 60; })) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.8)'
            }, {
                label: 'Productividad (%)',
                data: {!! json_encode($weeklyStats->pluck('avg_productivity')) !!},
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                yAxisID: 'y1'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Horas' }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    max: 100,
                    title: { display: true, text: 'Productividad %' }
                }
            }
        }
    });

    // Gráfico de actividades
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($activityStats->pluck('activity_type')) !!},
            datasets: [{
                data: {!! json_encode($activityStats->pluck('count')) !!},
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ]
            }]
        }
    });
</script>
@endsection
@endsection