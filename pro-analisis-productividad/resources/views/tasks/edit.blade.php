@extends('layouts.app')

@section('title', 'Editar Tarea: ' . $task->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-main">
                    <i class="fas fa-edit me-2 text-accent"></i>Editar Tarea
                </h1>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Tareas
                </a>
            </div>

            <!-- Información Actual de la Tarea -->
            <div class="card-custom mb-4">
                <div class="card-header-custom bg-dark">
                    <h6 class="card-title-custom mb-0 text-info">
                        <i class="fas fa-info-circle me-2"></i>Información Actual
                    </h6>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted d-block">Estado Actual</small>
                                @php $statusBadge = $task->status_badge; @endphp
                                <span class="badge-custom bg-{{ $statusBadge['color'] }} fs-6">
                                    {{ $statusBadge['text'] }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted d-block">Prioridad Actual</small>
                                @php $priorityBadge = $task->priority_badge; @endphp
                                <span class="badge-custom bg-{{ $priorityBadge['color'] }} fs-6">
                                    {{ $priorityBadge['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($task->due_date)
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted d-block">Fecha Límite Actual</small>
                            <span class="text-light {{ $task->is_overdue ? 'text-danger' : '' }}">
                                {{ $task->due_date->format('d/m/Y') }}
                                @if($task->is_overdue)
                                    <span class="badge bg-danger ms-2">Vencida</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">
                        <i class="fas fa-edit me-2 text-accent"></i>
                        Editando: <span class="text-accent">{{ Str::limit($task->title, 50) }}</span>
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Título y Proyecto -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="title" class="form-label text-main">
                                        <i class="fas fa-heading me-2 text-accent"></i>Título de la Tarea *
                                    </label>
                                    <input type="text" class="form-control-custom" id="title" name="title" 
                                           value="{{ old('title', $task->title) }}" required 
                                           placeholder="Ej: Desarrollar funcionalidad de reportes">
                                    @error('title')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="project_id" class="form-label text-main">
                                        <i class="fas fa-folder me-2 text-accent"></i>Proyecto *
                                    </label>
                                    <select class="form-select-custom" id="project_id" name="project_id" required>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label text-main">
                                <i class="fas fa-align-left me-2 text-accent"></i>Descripción
                            </label>
                            <textarea class="form-control-custom" id="description" name="description" 
                                      rows="4" placeholder="Describe los detalles específicos de la tarea...">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Configuraciones de la Tarea -->
                        <div class="card-custom border-custom mb-4">
                            <div class="card-header-custom bg-dark">
                                <h6 class="card-title-custom mb-0 text-accent">
                                    <i class="fas fa-cog me-2"></i>Configuración de la Tarea
                                </h6>
                            </div>
                            <div class="card-body-custom">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="status" class="form-label text-main">
                                                <i class="fas fa-tasks me-2"></i>Estado *
                                            </label>
                                            <select class="form-select-custom" id="status" name="status" required>
                                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>
                                                    ⏳ Pendiente
                                                </option>
                                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>
                                                    🚀 En Progreso
                                                </option>
                                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>
                                                    ✅ Completada
                                                </option>
                                                <option value="cancelled" {{ old('status', $task->status) == 'cancelled' ? 'selected' : '' }}>
                                                    ❌ Cancelada
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger small mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="priority" class="form-label text-main">
                                                <i class="fas fa-flag me-2"></i>Prioridad *
                                            </label>
                                            <select class="form-select-custom" id="priority" name="priority" required>
                                                <option value="1" {{ old('priority', $task->priority) == 1 ? 'selected' : '' }}>
                                                    🟢 Baja
                                                </option>
                                                <option value="2" {{ old('priority', $task->priority) == 2 ? 'selected' : '' }}>
                                                    🟡 Media
                                                </option>
                                                <option value="3" {{ old('priority', $task->priority) == 3 ? 'selected' : '' }}>
                                                    🔴 Alta
                                                </option>
                                            </select>
                                            @error('priority')
                                                <div class="text-danger small mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label text-main">
                                                <i class="fas fa-calendar me-2"></i>Fecha Límite
                                            </label>
                                            <input type="date" class="form-control-custom" id="due_date" name="due_date" 
                                                   value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                                                   min="{{ date('Y-m-d') }}">
                                            @error('due_date')
                                                <div class="text-danger small mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="estimated_minutes" class="form-label text-main">
                                                <i class="fas fa-clock me-2"></i>Tiempo Estimado (min)
                                            </label>
                                            <input type="number" class="form-control-custom" id="estimated_minutes" name="estimated_minutes" 
                                                   value="{{ old('estimated_minutes', $task->estimated_minutes) }}" 
                                                   placeholder="120" 
                                                   min="0" 
                                                   max="1440">
                                            @error('estimated_minutes')
                                                <div class="text-danger small mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tiempo Real Trabajado (solo lectura) -->
                                @if($task->actual_minutes > 0)
                                <div class="row mt-3 pt-3 border-top border-gray-600">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">Tiempo Real Trabajado</small>
                                                <span class="text-light fw-bold">
                                                    {{ floor($task->actual_minutes / 60) }}h {{ $task->actual_minutes % 60 }}m
                                                </span>
                                            </div>
                                            @if($task->estimated_minutes)
                                            <div class="text-end">
                                                <small class="text-muted d-block">Progreso</small>
                                                <span class="text-{{ $task->progress_percentage >= 100 ? 'success' : ($task->progress_percentage >= 50 ? 'warning' : 'info') }} fw-bold">
                                                    {{ $task->progress_percentage }}%
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-gray-600">
                            <div>
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info me-2">
                                    <i class="fas fa-eye me-2"></i>Ver Detalles
                                </a>
                                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-accent btn-lg">
                                    <i class="fas fa-save me-2"></i>Actualizar Tarea
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card-custom mt-4">
                <div class="card-header-custom">
                    <h6 class="card-title-custom mb-0 text-info">
                        <i class="fas fa-history me-2"></i>Información de la Tarea
                    </h6>
                </div>
                <div class="card-body-custom">
                    <div class="row text-light">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <small class="text-muted d-block">Creada</small>
                                <span>{{ $task->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <small class="text-muted d-block">Última actualización</small>
                                <span>{{ $task->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <small class="text-muted d-block">Proyecto</small>
                                <span class="badge-custom bg-secondary">{{ $task->project->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
}

.form-control-custom {
    background-color: var(--bg-dark);
    border: 2px solid var(--border-color);
    color: var(--text-main);
    border-radius: 10px;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control-custom::placeholder {
    color: var(--text-muted);
    opacity: 0.7;
}

.form-control-custom:focus {
    background-color: var(--bg-dark);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.3rem rgba(126, 87, 194, 0.25);
    outline: none;
}

.form-select-custom {
    background-color: var(--bg-dark);
    border: 2px solid var(--border-color);
    color: var(--text-main);
    border-radius: 10px;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

.form-select-custom:focus {
    background-color: var(--bg-dark);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.3rem rgba(126, 87, 194, 0.25);
    outline: none;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-light);
    font-size: 0.95rem;
}

.btn-accent {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-accent:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(126, 87, 194, 0.3);
}

.btn-outline-secondary {
    border: 2px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    border-color: var(--text-muted);
    color: var(--text-light);
    background-color: rgba(255,255,255,0.05);
}

.btn-outline-info {
    border: 2px solid var(--info);
    color: var(--info);
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-info:hover {
    background-color: var(--info);
    color: white;
}

.bg-dark {
    background-color: var(--bg-dark) !important;
}

.text-light {
    color: var(--text-light) !important;
}

.text-muted {
    color: var(--text-muted) !important;
}

.text-success {
    color: var(--success) !important;
}

.text-warning {
    color: var(--warning) !important;
}

.text-danger {
    color: var(--danger) !important;
}

.text-info {
    color: var(--info) !important;
}

.card-custom.border-custom {
    border: 2px solid var(--border-color) !important;
}

.card-header-custom.bg-dark {
    background-color: var(--bg-dark) !important;
    border-bottom: 2px solid var(--border-color);
}

.badge-custom {
    background-color: rgba(126, 87, 194, 0.2);
    color: var(--accent);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    border: 1px solid rgba(126, 87, 194, 0.3);
    font-weight: 500;
}

.badge-custom.fs-6 {
    font-size: 0.9em !important;
    padding: 8px 16px;
}

/* Mejorar la legibilidad de las opciones del select */
.form-select-custom option {
    background-color: var(--bg-dark);
    color: var(--text-main);
    padding: 0.5rem;
}

/* Estilos para el textarea */
.form-control-custom textarea {
    resize: vertical;
    min-height: 120px;
}

/* Mejorar los íconos */
.fas {
    opacity: 0.9;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
    
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between > div {
        width: 100%;
        text-align: center;
    }
}
</style>
@endpush