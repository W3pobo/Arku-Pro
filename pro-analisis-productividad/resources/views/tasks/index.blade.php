@extends('layouts.app')

@section('title', 'Gestión de Tareas')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header con estadísticas -->
            <div class="dashboard-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="text-main mb-2">
                            <i class="fas fa-tasks me-3 text-accent"></i>Gestión de Tareas
                        </h1>
                        <p class="text-light mb-0">Administra y organiza todas tus tareas en un solo lugar</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Tarea
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas Mejoradas -->
            <div class="row mb-5">
                @php
                    $statCards = [
                        ['count' => $stats['total'], 'label' => 'Total Tareas', 'icon' => 'fas fa-clipboard-list', 'color' => 'primary', 'bg' => 'primary-bg'],
                        ['count' => $stats['pending'], 'label' => 'Pendientes', 'icon' => 'fas fa-clock', 'color' => 'warning', 'bg' => 'warning-bg'],
                        ['count' => $stats['in_progress'], 'label' => 'En Progreso', 'icon' => 'fas fa-spinner', 'color' => 'info', 'bg' => 'info-bg'],
                        ['count' => $stats['completed'], 'label' => 'Completadas', 'icon' => 'fas fa-check-circle', 'color' => 'success', 'bg' => 'success-bg'],
                        ['count' => $stats['overdue'], 'label' => 'Vencidas', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'danger', 'bg' => 'danger-bg'],
                    ];
                @endphp

                @foreach($statCards as $card)
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="stat-card {{ $card['bg'] }} h-100">
                        <div class="stat-card-body">
                            <div class="stat-icon">
                                <i class="{{ $card['icon'] }}"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">{{ $card['count'] }}</h3>
                                <p class="stat-label">{{ $card['label'] }}</p>
                            </div>
                        </div>
                        <div class="stat-wave"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Panel de Filtros Mejorado -->
            <div class="card filter-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2 text-primary"></i>Filtros y Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tasks.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-2"></i>Estado
                            </label>
                            <select name="status" class="form-select">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Todos los estados</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>⏳ Pendiente</option>
                                <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>🔄 En Progreso</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>✅ Completada</option>
                                <option value="on_hold" {{ $status == 'on_hold' ? 'selected' : '' }}>⏸️ En Pausa</option>
                                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>❌ Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-folder me-2"></i>Proyecto
                            </label>
                            <select name="project_id" class="form-select">
                                <option value="">Todos los proyectos</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-sort me-2"></i>Ordenar por
                            </label>
                            <select name="sort" class="form-select">
                                <option value="due_date" {{ request('sort') == 'due_date' ? 'selected' : '' }}>Fecha de vencimiento</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Prioridad</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Fecha de creación</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Título</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Aplicar Filtros
                            </button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fas fa-refresh me-2"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Tareas Mejorada -->
            <div class="card tasks-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-check me-2 text-primary"></i>Lista de Tareas
                        <span class="badge bg-primary ms-2">{{ $tasks->total() }}</span>
                    </h5>
                    <div class="header-actions">
                        <span class="text-light me-3">{{ $tasks->count() }} de {{ $tasks->total() }} tareas</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-4">Tarea</th>
                                        <th>Proyecto</th>
                                        <th>Prioridad</th>
                                        <th>Estado</th>
                                        <th>Vencimiento</th>
                                        <th>Tiempo</th>
                                        <th class="text-center pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $task)
                                    <tr class="task-row {{ $task->is_overdue ? 'table-danger' : '' }} {{ $task->status == 'completed' ? 'completed-task' : '' }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-start">
                                                <div class="task-checkbox flex-shrink-0 me-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input task-status-toggle" 
                                                               data-task-id="{{ $task->id }}"
                                                               {{ $task->status == 'completed' ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="task-content flex-grow-1">
                                                    <h6 class="task-title mb-1 text-light">{{ $task->title }}</h6>
                                                    @if($task->description)
                                                        <p class="task-description mb-0 text-muted">{{ Str::limit($task->description, 70) }}</p>
                                                    @endif
                                                    <div class="task-meta mt-1">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar-plus me-1"></i>
                                                            Creada: {{ $task->created_at->format('d/m/Y') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="project-badge">
                                                <i class="fas fa-folder me-1"></i>
                                                {{ $task->project->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @php 
                                                $priorityBadge = $task->priority_badge ?? ['text' => 'Prioridad ' . $task->priority];
                                                $priorityIcons = [
                                                    1 => 'fas fa-flag',
                                                    2 => 'fas fa-flag',
                                                    3 => 'fas fa-flag'
                                                ];
                                            @endphp
                                            <span class="priority-badge priority-{{ $task->priority }}">
                                                <i class="{{ $priorityIcons[$task->priority] ?? 'fas fa-flag' }} me-1"></i>
                                                {{ $priorityBadge['text'] ?? 'Prioridad ' . $task->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            @php 
                                                $statusBadge = $task->status_badge ?? ['text' => ucfirst($task->status)];
                                                $statusTexts = [
                                                    'pending' => '⏳ Pendiente',
                                                    'in_progress' => '🔄 En Progreso',
                                                    'completed' => '✅ Completada',
                                                    'on_hold' => '⏸️ En Pausa',
                                                    'cancelled' => '❌ Cancelada'
                                                ];
                                            @endphp
                                            <span class="status-badge status-{{ $task->status }}">
                                                {{ $statusTexts[$task->status] ?? ($statusBadge['text'] ?? ucfirst($task->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->due_date)
                                                <div class="due-date {{ $task->is_overdue ? 'text-danger' : 'text-light' }}">
                                                    <i class="fas fa-calendar-day me-1"></i>
                                                    {{ $task->due_date->format('d/m/Y') }}
                                                    @if($task->is_overdue)
                                                        <div class="badge bg-danger mt-1">Vencida</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-calendar-times me-1"></i>
                                                    Sin fecha
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="time-estimate text-light">
                                                @if($task->estimated_minutes)
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ floor($task->estimated_minutes / 60) }}h {{ $task->estimated_minutes % 60 }}m
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Sin estimar
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="action-buttons">
                                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" 
                                                            onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">
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
                        
                        <!-- Paginación Mejorada -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info text-muted">
                                    Mostrando {{ $tasks->firstItem() }} - {{ $tasks->lastItem() }} de {{ $tasks->total() }} tareas
                                </div>
                                <div class="pagination-custom">
                                    {{ $tasks->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-4">
                                <i class="fas fa-tasks fa-4x text-primary"></i>
                            </div>
                            <h4 class="text-light mb-3">No hay tareas encontradas</h4>
                            <p class="text-muted mb-4">No se encontraron tareas que coincidan con tus filtros.</p>
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-plus-circle me-2"></i>Crear Nueva Tarea
                            </a>
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-refresh me-2"></i>Ver Todas las Tareas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para la página de tareas */
.dashboard-header {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
    padding: 2rem;
    border-radius: 16px;
    border: 1px solid var(--border-color);
}

/* Tarjetas de Estadísticas */
.stat-card {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-dark) 100%);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.stat-card-body {
    display: flex;
    align-items: center;
    position: relative;
    z-index: 2;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--text-primary) !important;
}

.stat-label {
    color: var(--text-muted) !important;
    font-size: 0.875rem;
    margin-bottom: 0;
}

.stat-wave {
    position: absolute;
    bottom: -10px;
    right: -10px;
    width: 80px;
    height: 80px;
    opacity: 0.1;
    background: currentColor;
    border-radius: 50%;
}

/* Colores para las tarjetas de estadísticas */
.primary-bg { 
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%) !important; 
    color: white !important; 
}
.warning-bg { 
    background: linear-gradient(135deg, var(--warning) 0%, #fbbf24 100%) !important; 
    color: white !important; 
}
.info-bg { 
    background: linear-gradient(135deg, var(--info) 0%, #60a5fa 100%) !important; 
    color: white !important; 
}
.success-bg { 
    background: linear-gradient(135deg, var(--success) 0%, #34d399 100%) !important; 
    color: white !important; 
}
.danger-bg { 
    background: linear-gradient(135deg, var(--danger) 0%, #f87171 100%) !important; 
    color: white !important; 
}

.primary-bg .stat-icon { background: rgba(255,255,255,0.2); }
.warning-bg .stat-icon { background: rgba(255,255,255,0.2); }
.info-bg .stat-icon { background: rgba(255,255,255,0.2); }
.success-bg .stat-icon { background: rgba(255,255,255,0.2); }
.danger-bg .stat-icon { background: rgba(255,255,255,0.2); }

.primary-bg .stat-number,
.primary-bg .stat-label,
.warning-bg .stat-number,
.warning-bg .stat-label,
.info-bg .stat-number,
.info-bg .stat-label,
.success-bg .stat-number,
.success-bg .stat-label,
.danger-bg .stat-number,
.danger-bg .stat-label {
    color: white !important;
}

/* Elementos de la tarea */
.task-checkbox .form-check-input {
    width: 1.2em;
    height: 1.2em;
    margin-top: 0.25em;
}

.task-checkbox .form-check-input:checked {
    background-color: var(--success) !important;
    border-color: var(--success) !important;
}

.task-title {
    color: var(--text-primary) !important;
    font-weight: 600;
    font-size: 1rem;
}

.task-description {
    color: var(--text-muted) !important;
    font-size: 0.875rem;
    line-height: 1.4;
}

.task-meta {
    font-size: 0.75rem;
}

/* Badges personalizados */
.project-badge {
    background: rgba(148, 163, 184, 0.1) !important;
    color: var(--text-primary) !important;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    border: 1px solid var(--border-color) !important;
}

.priority-badge, .status-badge {
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
}

.priority-1 { 
    background: rgba(16, 185, 129, 0.15) !important; 
    color: var(--success) !important; 
    border: 1px solid var(--success) !important; 
}
.priority-2 { 
    background: rgba(245, 158, 11, 0.15) !important; 
    color: var(--warning) !important; 
    border: 1px solid var(--warning) !important; 
}
.priority-3 { 
    background: rgba(239, 68, 68, 0.15) !important; 
    color: var(--danger) !important; 
    border: 1px solid var(--danger) !important; 
}

.status-pending { 
    background: rgba(245, 158, 11, 0.15) !important; 
    color: var(--warning) !important; 
    border: 1px solid var(--warning) !important; 
}
.status-in_progress { 
    background: rgba(59, 130, 246, 0.15) !important; 
    color: var(--info) !important; 
    border: 1px solid var(--info) !important; 
}
.status-completed { 
    background: rgba(16, 185, 129, 0.15) !important; 
    color: var(--success) !important; 
    border: 1px solid var(--success) !important; 
}
.status-on_hold { 
    background: rgba(148, 163, 184, 0.15) !important; 
    color: var(--text-muted) !important; 
    border: 1px solid var(--text-muted) !important; 
}
.status-cancelled { 
    background: rgba(239, 68, 68, 0.15) !important; 
    color: var(--danger) !important; 
    border: 1px solid var(--danger) !important; 
}

/* Estados de tareas */
.completed-task {
    opacity: 0.7;
    background: rgba(16, 185, 129, 0.05) !important;
}

.task-row:hover {
    background: rgba(126, 87, 194, 0.05) !important;
}

/* Botones de acción */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

/* Estado vacío */
.empty-state {
    padding: 4rem 2rem;
}

.empty-icon {
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-header {
        padding: 1.5rem;
    }
    
    .stat-card-body {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle de estado de tarea
    document.querySelectorAll('.task-status-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const status = this.checked ? 'completed' : 'pending';
            
            updateTaskStatus(taskId, status);
        });
    });
});

function updateTaskStatus(taskId, status) {
    fetch(`/tasks/${taskId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Agregar animación de actualización
            const taskRow = document.querySelector(`[data-task-id="${taskId}"]`).closest('.task-row');
            taskRow.classList.add('status-updated');
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        location.reload();
    });
}
</script>
@endpush