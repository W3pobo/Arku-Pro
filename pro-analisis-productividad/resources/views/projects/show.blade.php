@extends('layouts.app')

@section('title', 'Detalles del Proyecto')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">{{ $project->name }}</h1>
                <p class="page-subtitle">Detalles y registros de tiempo del proyecto</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna de Información del Proyecto -->
        <div class="col-lg-4 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Detalles del Proyecto
                    </h3>
                    <div class="table-actions">
                        <a href="{{ route('projects.edit', $project) }}" class="btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                    </div>
                </div>
                <div class="chart-body">
                    <!-- Descripción -->
                    <div class="detail-section mb-4">
                        <label class="form-label">
                            <i class="fas fa-comment me-2"></i>Descripción
                        </label>
                        <div class="description-box">
                            {{ $project->description ?: 'No hay descripción disponible.' }}
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="detail-section mb-4">
                        <label class="form-label">
                            <i class="fas fa-chart-line me-2"></i>Estado
                        </label>
                        <div class="status-badge status-{{ $project->status }}">
                            @if($project->status == 'active')
                                <i class="fas fa-play-circle me-2"></i>Activo
                            @elseif($project->status == 'completed')
                                <i class="fas fa-check-circle me-2"></i>Completado
                            @elseif($project->status == 'paused')
                                <i class="fas fa-pause-circle me-2"></i>Pausado
                            @else
                                <i class="fas fa-times-circle me-2"></i>Cancelado
                            @endif
                        </div>
                    </div>

                    <!-- Horas Totales -->
                    <div class="detail-section mb-4">
                        <label class="form-label">
                            <i class="fas fa-clock me-2"></i>Horas Totales
                        </label>
                        <div class="total-hours-display">
                            <i class="fas fa-hourglass-half me-2"></i>
                            {{ number_format($totalHours, 1) }} horas
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row g-3">
                        @if($project->start_date)
                        <div class="col-6">
                            <div class="detail-section">
                                <label class="form-label small-label">
                                    <i class="fas fa-play me-2"></i>Inicio
                                </label>
                                <div class="date-display">
                                    {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($project->deadline)
                        <div class="col-6">
                            <div class="detail-section">
                                <label class="form-label small-label">
                                    <i class="fas fa-flag me-2"></i>Límite
                                </label>
                                <div class="date-display">
                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Color -->
                    @if($project->color)
                    <div class="detail-section">
                        <label class="form-label">
                            <i class="fas fa-palette me-2"></i>Color
                        </label>
                        <div class="color-display-wrapper">
                            <div class="color-display" style="background-color: {{ $project->color }}"></div>
                            <span class="color-value">{{ $project->color }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sección de Acciones Peligrosas -->
            <div class="chart-card mt-4 danger-section">
                <div class="chart-header">
                    <h3 class="chart-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Zona de Peligro
                    </h3>
                </div>
                <div class="chart-body">
                    <div class="danger-content">
                        <div class="danger-icon">
                            <i class="fas fa-trash"></i>
                        </div>
                        <div class="danger-text">
                            <h4>Eliminar Proyecto</h4>
                            <p>Esta acción no se puede deshacer. Se eliminarán todos los datos asociados.</p>
                        </div>
                        <div class="danger-action">
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash me-2"></i>
                                    Eliminar Proyecto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna de Registros de Tiempo -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-clock me-2"></i>
                        Registros de Tiempo
                    </h3>
                    <div class="table-actions">
                        <span class="table-info">{{ $project->timeTrackings->count() }} registros</span>
                        <a href="{{ route('time-trackings.create') }}" class="btn-primary">
                            <i class="fas fa-plus me-2"></i>Nuevo Registro
                        </a>
                    </div>
                </div>
                <div class="chart-body">
                    @if($project->timeTrackings->count() > 0)
                        <div class="time-trackings-list">
                            @foreach($project->timeTrackings as $tracking)
                            <div class="time-tracking-item">
                                <div class="tracking-header">
                                    <div class="tracking-main">
                                        <div class="tracking-type">
                                            <i class="fas fa-tasks me-2"></i>
                                            {{ $tracking->activity_type }}
                                        </div>
                                        <div class="tracking-duration">
                                            <i class="fas fa-clock me-2"></i>
                                            {{ $tracking->duration_minutes }} minutos
                                        </div>
                                    </div>
                                    <div class="tracking-date">
                                        {{ $tracking->start_time->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                
                                @if($tracking->description)
                                <div class="tracking-description">
                                    <i class="fas fa-align-left me-2"></i>
                                    {{ $tracking->description }}
                                </div>
                                @endif

                                @if($tracking->focus_level || $tracking->energy_level)
                                <div class="tracking-metrics">
                                    @if($tracking->focus_level)
                                    <div class="metric-tag">
                                        <i class="fas fa-bullseye me-1"></i>
                                        Foco: {{ $tracking->focus_level }}/10
                                    </div>
                                    @endif
                                    @if($tracking->energy_level)
                                    <div class="metric-tag">
                                        <i class="fas fa-bolt me-1"></i>
                                        Energía: {{ $tracking->energy_level }}/10
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="chart-empty">
                            <i class="fas fa-clock fa-4x mb-3"></i>
                            <h4>No hay registros de tiempo</h4>
                            <p class="text-muted">Comienza a trackear tu tiempo para ver tus estadísticas</p>
                            <a href="{{ route('time-trackings.create') }}" class="btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Crear primer registro
                            </a>
                        </div>
                    @endif
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

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    color: white;
}

.table-info {
    font-size: 0.85rem;
    color: var(--text-muted);
    background: rgba(255,255,255,0.05);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
}

.chart-body {
    padding: 2rem;
}

/* === DETALLES DEL PROYECTO === */
.detail-section {
    margin-bottom: 1.5rem;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.form-label {
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.form-label.small-label {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.description-box {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1rem;
    color: var(--text-light);
    line-height: 1.5;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-completed {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-paused {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.total-hours-display {
    display: flex;
    align-items: center;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--accent);
    background: rgba(126, 87, 194, 0.1);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(126, 87, 194, 0.3);
}

.date-display {
    color: var(--text-light);
    font-weight: 500;
    padding: 0.5rem 0;
}

.color-display-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.color-display {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.color-value {
    color: var(--text-muted);
    font-size: 0.875rem;
    font-family: monospace;
}

/* === LISTA DE REGISTROS DE TIEMPO === */
.time-trackings-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.time-tracking-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.time-tracking-item:hover {
    background: rgba(126, 87, 194, 0.05);
    border-color: rgba(126, 87, 194, 0.3);
}

.tracking-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.tracking-main {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.tracking-type {
    font-weight: 600;
    color: var(--text-light);
    display: flex;
    align-items: center;
}

.tracking-duration {
    color: var(--accent);
    font-weight: 600;
    display: flex;
    align-items: center;
}

.tracking-date {
    color: var(--text-muted);
    font-size: 0.875rem;
    background: rgba(255,255,255,0.05);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
}

.tracking-description {
    color: var(--text-light);
    line-height: 1.5;
    margin-bottom: 1rem;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: flex-start;
}

.tracking-description i {
    margin-top: 0.25rem;
    color: var(--text-muted);
}

.tracking-metrics {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.metric-tag {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    border: 1px solid var(--border-color);
}

/* === ESTADO VACÍO === */
.chart-empty {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--text-muted);
}

.chart-empty i {
    margin-bottom: 1rem;
    opacity: 0.5;
}

.chart-empty h4 {
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

/* === SECCIÓN DE PELIGRO === */
.danger-section {
    border-color: rgba(239, 68, 68, 0.3);
}

.danger-section .chart-header {
    border-bottom-color: rgba(239, 68, 68, 0.3);
}

.danger-section .chart-title {
    color: var(--danger);
}

.danger-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1rem 0;
}

.danger-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: rgba(239, 68, 68, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--danger);
    font-size: 1.5rem;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.danger-text {
    flex: 1;
}

.danger-text h4 {
    color: var(--danger);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.danger-text p {
    color: var(--text-muted);
    margin: 0;
    font-size: 0.9rem;
}

.btn-delete {
    background: linear-gradient(135deg, var(--danger), #dc2626);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    cursor: pointer;
    white-space: nowrap;
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    background: linear-gradient(135deg, #dc2626, var(--danger));
    color: white;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .chart-body {
        padding: 1rem;
    }
    
    .tracking-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .danger-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .danger-action {
        width: 100%;
    }
    
    .btn-delete {
        width: 100%;
        justify-content: center;
    }
    
    .color-display-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
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
    
    .time-tracking-item {
        padding: 1rem;
    }
    
    .tracking-metrics {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection