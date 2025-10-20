@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Registros de Tiempo</h1>
                <p class="page-subtitle">Gestiona y revisa tus sesiones de trabajo registradas</p>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert-success-custom">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <div class="alert-text">
                        <span class="alert-title">Éxito</span>
                        <span class="alert-message">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tarjeta Principal -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-clock me-2"></i>
                        Historial de Registros
                    </h3>
                    <div class="table-actions">
                        <a href="{{ route('time-trackings.create') }}" class="btn-primary">
                            <i class="fas fa-plus me-2"></i>Nuevo Registro
                        </a>
                        <span class="table-info">{{ $timeTrackings->count() }} registros</span>
                    </div>
                </div>
                <div class="chart-body">
                    @if($timeTrackings->count() > 0)
                        <div class="table-container">
                            <table class="performance-table">
                                <thead>
                                    <tr>
                                        <th class="project-col">Proyecto</th>
                                        <th class="time-col">Inicio</th>
                                        <th class="time-col">Fin</th>
                                        <th class="duration-col">Duración</th>
                                        <th class="type-col">Tipo</th>
                                        <th class="productivity-col">Productividad</th>
                                        <th class="actions-col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($timeTrackings as $tracking)
                                    <tr class="project-row">
                                        <td class="project-col">
                                            <div class="project-info">
                                                <div class="project-color" style="background-color: #7E57C2"></div>
                                                <div class="project-details">
                                                    <span class="project-name">{{ $tracking->project->name ?? 'Sin proyecto' }}</span>
                                                    @if($tracking->description)
                                                    <span class="project-description">{{ Str::limit($tracking->description, 30) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="time-col">
                                            <div class="time-display">
                                                <span class="time-date">{{ $tracking->start_time->format('d/m/Y') }}</span>
                                                <span class="time-hour">{{ $tracking->start_time->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="time-col">
                                            <div class="time-display">
                                                <span class="time-date">{{ $tracking->end_time->format('d/m/Y') }}</span>
                                                <span class="time-hour">{{ $tracking->end_time->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="duration-col">
                                            <div class="duration-value">
                                                <i class="fas fa-clock me-2"></i>
                                                {{ $tracking->duration_minutes }} min
                                            </div>
                                        </td>
                                        <td class="type-col">
                                            <span class="type-badge type-{{ Str::slug($tracking->activity_type) }}">
                                                {{ $tracking->activity_type }}
                                            </span>
                                        </td>
                                        <td class="productivity-col">
                                            <div class="productivity-display">
                                                <div class="progress-container">
                                                    <div class="progress-bar 
                                                        @if($tracking->productivity_score >= 80) progress-excellent
                                                        @elseif($tracking->productivity_score >= 50) progress-good
                                                        @else progress-poor
                                                        @endif" 
                                                        style="width: {{ $tracking->productivity_score }}%">
                                                        <span class="progress-text">{{ $tracking->productivity_score }}%</span>
                                                    </div>
                                                </div>
                                                <div class="productivity-details">
                                                    <small>Foco: {{ $tracking->focus_level ?? 'N/A' }}/10</small>
                                                    <small>Energía: {{ $tracking->energy_level ?? 'N/A' }}/10</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="actions-col">
                                            <div class="actions-group">
                                                <a href="{{ route('time-trackings.edit', $tracking) }}" 
                                                   class="btn-action btn-edit" 
                                                   title="Editar registro">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('time-trackings.destroy', $tracking) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete" 
                                                            onclick="return confirm('¿Estás seguro de eliminar este registro?')"
                                                            title="Eliminar registro">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="chart-empty">
                            <i class="fas fa-clock fa-4x mb-3"></i>
                            <h4>No hay registros de tiempo aún</h4>
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

    <!-- CTA para Reportes -->
    <div class="row">
        <div class="col-12">
            <div class="cta-card">
                <div class="cta-content">
                    <div class="cta-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="cta-text">
                        <h3>¿Quieres ver análisis detallados?</h3>
                        <p>Revisa tus reportes de productividad y métricas avanzadas.</p>
                    </div>
                    <div class="cta-action">
                        <a href="{{ route('reports.index') }}" class="btn-cta">
                            <i class="fas fa-chart-bar me-2"></i>
                            Ver Reportes
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

/* === ALERTAS === */
.alert-success-custom {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    backdrop-filter: blur(10px);
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.alert-icon {
    font-size: 1.5rem;
    color: var(--success);
}

.alert-text {
    display: flex;
    flex-direction: column;
}

.alert-title {
    font-weight: 600;
    color: var(--success);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.alert-message {
    color: var(--text-light);
    font-size: 0.95rem;
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

.chart-empty h4 {
    color: var(--text-light);
    margin-bottom: 0.5rem;
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

.project-col { width: 20%; }
.time-col { width: 12%; }
.duration-col { width: 10%; }
.type-col { width: 12%; }
.productivity-col { width: 20%; }
.actions-col { width: 10%; }

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

.time-display {
    display: flex;
    flex-direction: column;
}

.time-date {
    font-size: 0.85rem;
    color: var(--text-light);
    font-weight: 500;
}

.time-hour {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.duration-value {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: var(--text-light);
}

.type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid;
}

.type-development {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border-color: rgba(59, 130, 246, 0.3);
}

.type-design {
    background: rgba(168, 85, 247, 0.1);
    color: #a855f7;
    border-color: rgba(168, 85, 247, 0.3);
}

.type-meeting {
    background: rgba(14, 165, 233, 0.1);
    color: #0ea5e9;
    border-color: rgba(14, 165, 233, 0.3);
}

.type-research {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border-color: rgba(34, 197, 94, 0.3);
}

.type-other {
    background: rgba(100, 116, 139, 0.1);
    color: var(--text-muted);
    border-color: rgba(100, 116, 139, 0.3);
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

.actions-group {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-edit {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.btn-edit:hover {
    background: var(--info);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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
    
    .chart-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-actions {
        width: 100%;
        justify-content: space-between;
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
    
    .cta-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .time-display {
        align-items: center;
        text-align: center;
    }
    
    .productivity-details {
        flex-direction: column;
        gap: 0.25rem;
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
    
    .actions-group {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
    }
}
</style>
@endsection