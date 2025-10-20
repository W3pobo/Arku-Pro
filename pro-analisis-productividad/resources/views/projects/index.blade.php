@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header Mejorado -->
            <div class="page-header mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">Mis Proyectos</h1>
                        <p class="page-subtitle">Gestiona y organiza todos tus proyectos en un solo lugar</p>
                    </div>
                    <a href="{{ route('projects.create') }}" class="btn-create-project">
                        <i class="fas fa-plus me-2"></i>
                        Nuevo Proyecto
                    </a>
                </div>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="alert-custom alert-success mb-4">
                    <div class="alert-content">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Grid de Proyectos -->
            @if($projects->count() > 0)
                <div class="projects-grid">
                    @foreach($projects as $project)
                    <div class="project-card">
                        <!-- Header de la Tarjeta -->
                        <div class="project-card-header">
                            <div class="project-color-indicator" style="background-color: {{ $project->color ?? '#7E57C2' }}"></div>
                            <div class="project-title-section">
                                <h3 class="project-title">{{ $project->name }}</h3>
                                <span class="project-status status-{{ $project->status }}">
                                    @switch($project->status)
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
                                            {{ $project->status }}
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <!-- Contenido de la Tarjeta -->
                        <div class="project-card-body">
                            <p class="project-description">
                                {{ $project->description ? Str::limit($project->description, 120) : 'Sin descripción...' }}
                            </p>
                            
                            <!-- Estadísticas del Proyecto -->
                            <div class="project-stats">
                                <div class="stat-item">
                                    <i class="fas fa-clock stat-icon"></i>
                                    <div class="stat-info">
                                        <span class="stat-value">{{ number_format($project->timeTrackings->sum('duration_minutes') / 60, 1) }}h</span>
                                        <span class="stat-label">Horas registradas</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-tasks stat-icon"></i>
                                    <div class="stat-info">
                                        <span class="stat-value">{{ $project->timeTrackings->count() }}</span>
                                        <span class="stat-label">Registros</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fechas del Proyecto -->
                            @if($project->start_date || $project->deadline)
                            <div class="project-dates">
                                @if($project->start_date)
                                <div class="date-item">
                                    <i class="fas fa-play-circle date-icon"></i>
                                    <span class="date-text">Inicio: {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</span>
                                </div>
                                @endif
                                @if($project->deadline)
                                <div class="date-item">
                                    <i class="fas fa-flag-checkered date-icon"></i>
                                    <span class="date-text deadline {{ \Carbon\Carbon::parse($project->deadline)->isPast() ? 'deadline-passed' : '' }}">
                                        Límite: {{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Footer de la Tarjeta -->
                        <div class="project-card-footer">
                            <div class="project-actions">
                                <a href="{{ route('projects.show', $project) }}" class="btn-action btn-view" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                    <span class="btn-tooltip">Ver</span>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}" class="btn-action btn-edit" title="Editar proyecto">
                                    <i class="fas fa-edit"></i>
                                    <span class="btn-tooltip">Editar</span>
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" 
                                            title="Eliminar proyecto"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este proyecto?')">
                                        <i class="fas fa-trash"></i>
                                        <span class="btn-tooltip">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Estado Vacío -->
                <div class="empty-state">
                    <div class="empty-state-content">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3 class="empty-state-title">No tienes proyectos aún</h3>
                        <p class="empty-state-description">
                            Comienza creando tu primer proyecto para organizar y gestionar tu trabajo de manera eficiente.
                        </p>
                        <a href="{{ route('projects.create') }}" class="btn-create-first">
                            <i class="fas fa-plus me-2"></i>
                            Crear Primer Proyecto
                        </a>
                    </div>
                </div>
            @endif
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
    padding: 0 0.5rem;
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

/* === BOTONES PRINCIPALES === */
.btn-create-project {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 
        0 4px 15px rgba(126, 87, 194, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.btn-create-project::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s;
}

.btn-create-project:hover::before {
    left: 100%;
}

.btn-create-project:hover {
    transform: translateY(-3px);
    box-shadow: 
        0 8px 25px rgba(126, 87, 194, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
    color: white;
}

/* === ALERTAS === */
.alert-custom {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: between;
    align-items: center;
    backdrop-filter: blur(10px);
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.alert-icon {
    color: var(--success);
    font-size: 1.2rem;
}

.alert-close {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);
}

/* === GRID DE PROYECTOS === */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

/* === TARJETAS DE PROYECTO === */
.project-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
}

.project-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--card-shadow-hover);
    border-color: var(--accent-light);
}

.project-card-header {
    padding: 1.5rem 1.5rem 1rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.project-color-indicator {
    width: 4px;
    height: 40px;
    border-radius: 2px;
    flex-shrink: 0;
}

.project-title-section {
    flex: 1;
}

.project-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.project-status {
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

/* === CUERPO DE LA TARJETA === */
.project-card-body {
    padding: 1.5rem;
}

.project-description {
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.project-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.stat-icon {
    color: var(--accent);
    font-size: 1.1rem;
    width: 20px;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-weight: 600;
    color: var(--text-light);
    font-size: 1.1rem;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.project-dates {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.date-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.date-icon {
    color: var(--text-muted);
    width: 16px;
}

.date-text {
    color: var(--text-muted);
}

.deadline-passed {
    color: var(--danger);
    font-weight: 500;
}

/* === FOOTER DE LA TARJETA === */
.project-card-footer {
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.02);
    border-top: 1px solid var(--border-color);
}

.project-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-action {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    color: var(--text-muted);
}

.btn-action:hover {
    transform: translateY(-2px);
}

.btn-action:hover .btn-tooltip {
    opacity: 1;
    transform: translateY(-100%) translateX(-50%);
}

.btn-tooltip {
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%) translateY(0);
    background: var(--bg-dark);
    color: var(--text-light);
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    transition: all 0.3s ease;
    pointer-events: none;
    border: 1px solid var(--border-color);
}

.btn-view {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.btn-view:hover {
    background: var(--info);
    color: white;
}

.btn-edit {
    background: rgba(126, 87, 194, 0.1);
    border: 1px solid rgba(126, 87, 194, 0.2);
}

.btn-edit:hover {
    background: var(--accent);
    color: white;
}

.btn-delete {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
}

/* === ESTADO VACÍO === */
.empty-state {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    padding: 3rem;
}

.empty-state-content {
    text-align: center;
    max-width: 500px;
}

.empty-state-icon {
    font-size: 4rem;
    color: var(--accent);
    margin-bottom: 1.5rem;
    opacity: 0.7;
}

.empty-state-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 1rem;
}

.empty-state-description {
    color: var(--text-muted);
    font-size: 1.1rem;
    line-height: 1.5;
    margin-bottom: 2rem;
}

.btn-create-first {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-create-first:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(126, 87, 194, 0.4);
    color: white;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .projects-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .page-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .project-stats {
        grid-template-columns: 1fr;
    }
    
    .project-card-header {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .project-color-indicator {
        width: 100%;
        height: 4px;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .project-card-body {
        padding: 1rem;
    }
    
    .project-actions {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efectos de hover mejorados para las tarjetas
    const projectCards = document.querySelectorAll('.project-card');
    
    projectCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Auto-ocultar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert-custom');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentElement) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
});
</script>
@endsection