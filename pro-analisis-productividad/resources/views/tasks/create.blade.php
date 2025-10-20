@extends('layouts.app')

@section('title', 'Crear Nueva Tarea')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Header Mejorado -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="text-main mb-2">Crear Nueva Tarea</h1>
                    <p class="text-secondary mb-0">Complete la información requerida para crear una nueva tarea</p>
                </div>
                <a href="{{ route('tasks.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Tareas
                </a>
            </div>

            <!-- Formulario Principal -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">
                        <i class="fas fa-plus-circle me-2 text-accent"></i>
                        Información de la Tarea
                    </h5>
                </div>
                
                <div class="card-body-custom">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        
                        <!-- Sección 1: Información Básica -->
                        <div class="form-section mb-5">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Información Básica
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="title" class="form-label">
                                            <i class="fas fa-heading me-2 text-accent"></i>Título de la Tarea *
                                        </label>
                                        <input type="text" class="form-control-custom" id="title" name="title" 
                                               value="{{ old('title') }}" required 
                                               placeholder="Ej: Desarrollar funcionalidad de reportes">
                                        @error('title')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="project_id" class="form-label">
                                            <i class="fas fa-folder me-2 text-accent"></i>Proyecto *
                                        </label>
                                        <select class="form-select-custom" id="project_id" name="project_id" required>
                                            <option value="">Seleccionar proyecto</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Descripción -->
                        <div class="form-section mb-5">
                            <h6 class="section-title">
                                <i class="fas fa-align-left me-2"></i>
                                Descripción Detallada
                            </h6>
                            
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    Descripción de la Tarea
                                </label>
                                <textarea class="form-control-custom" id="description" name="description" 
                                          rows="4" placeholder="Describe los detalles específicos de la tarea, requisitos, y cualquier información relevante...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección 3: Configuración -->
                        <div class="form-section mb-5">
                            <h6 class="section-title">
                                <i class="fas fa-cog me-2"></i>
                                Configuración de la Tarea
                            </h6>
                            
                            <div class="config-grid">
                                <div class="row g-4">
                                    <!-- Estado -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label for="status" class="form-label">
                                                <i class="fas fa-tasks me-2"></i>Estado *
                                            </label>
                                            <select class="form-select-custom" id="status" name="status" required>
                                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                                    ⏳ Pendiente
                                                </option>
                                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                                    🔄 En Progreso
                                                </option>
                                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                                    ✅ Completada
                                                </option>
                                                <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>
                                                    ⏸️ En Pausa
                                                </option>
                                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                                    ❌ Cancelada
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Prioridad -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label for="priority" class="form-label">
                                                <i class="fas fa-flag me-2"></i>Prioridad *
                                            </label>
                                            <select class="form-select-custom" id="priority" name="priority" required>
                                                <option value="1" {{ old('priority', 1) == 1 ? 'selected' : '' }}>
                                                    🟢 Baja
                                                </option>
                                                <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>
                                                    🟡 Media
                                                </option>
                                                <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>
                                                    🔴 Alta
                                                </option>
                                            </select>
                                            @error('priority')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Fecha Límite -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label for="due_date" class="form-label">
                                                <i class="fas fa-calendar me-2"></i>Fecha Límite
                                            </label>
                                            <input type="date" class="form-control-custom" id="due_date" name="due_date" 
                                                   value="{{ old('due_date') }}"
                                                   min="{{ date('Y-m-d') }}">
                                            @error('due_date')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Tiempo Estimado -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label for="estimated_minutes" class="form-label">
                                                <i class="fas fa-clock me-2"></i>Tiempo Estimado
                                            </label>
                                            <input type="number" class="form-control-custom" id="estimated_minutes" name="estimated_minutes" 
                                                   value="{{ old('estimated_minutes') }}" 
                                                   placeholder="Auto-calculado" 
                                                   min="0" 
                                                   max="1440"
                                                   readonly>
                                            @error('estimated_minutes')
                                                <div class="error-message">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Visualización de Tiempo -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="time-display-card">
                                            <label class="form-label mb-3">
                                                <i class="fas fa-hourglass-half me-2"></i>Tiempo Convertido
                                            </label>
                                            <div class="time-display-container">
                                                <div id="time-display" class="time-display-value">
                                                    @if(old('estimated_minutes'))
                                                        {{ floor(old('estimated_minutes') / 60) }}h {{ old('estimated_minutes') % 60 }}m
                                                    @else
                                                        0h 0m
                                                    @endif
                                                </div>
                                                <small class="time-display-label">Horas / Minutos</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center pt-4 border-top border-gray-600">
                                <a href="{{ route('tasks.index') }}" class="btn-cancel">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn-create">
                                    <i class="fas fa-plus-circle me-2"></i>Crear Tarea
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel de Consejos -->
            <div class="card-custom mt-5">
                <div class="card-header-custom">
                    <h6 class="card-title-custom mb-0 text-info">
                        <i class="fas fa-lightbulb me-2"></i>Consejos para Tareas Efectivas
                    </h6>
                </div>
                <div class="card-body-custom">
                    <div class="tips-grid">
                        <div class="tip-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <div>
                                <strong>Títulos claros:</strong> 
                                <span class="text-light">Describe la tarea específicamente</span>
                            </div>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <div>
                                <strong>Descripciones detalladas:</strong> 
                                <span class="text-light">Incluye todos los requisitos</span>
                            </div>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <div>
                                <strong>Estado inicial:</strong> 
                                <span class="text-light">Comienza como "Pendiente"</span>
                            </div>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <div>
                                <strong>Prioridades realistas:</strong> 
                                <span class="text-light">Asigna según la urgencia</span>
                            </div>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <div>
                                <strong>Tiempos precisos:</strong> 
                                <span class="text-light">Estima duraciones realistas</span>
                            </div>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check text-success me-2"></i>
                            <div>
                                <strong>Fechas límite:</strong> 
                                <span class="text-light">Establece plazos alcanzables</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dueDateInput = document.getElementById('due_date');
    const minutesInput = document.getElementById('estimated_minutes');
    const timeDisplay = document.getElementById('time-display');
    
    function updateTimeDisplay() {
        const minutes = parseInt(minutesInput.value) || 0;
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;
        
        let displayText = '';
        if (hours > 0) {
            displayText = `${hours}h ${remainingMinutes}m`;
        } else {
            displayText = `${remainingMinutes}m`;
        }
        
        timeDisplay.textContent = displayText;
        
        // Cambiar color según la duración
        if (minutes > 480) { // Más de 8 horas
            timeDisplay.className = 'time-display-value text-danger';
        } else if (minutes > 240) { // Más de 4 horas
            timeDisplay.className = 'time-display-value text-warning';
        } else {
            timeDisplay.className = 'time-display-value text-success';
        }
    }
    
    function calculateEstimatedTime(dueDate) {
        if (!dueDate) {
            minutesInput.value = '';
            updateTimeDisplay();
            return;
        }
        
        const selectedDate = new Date(dueDate);
        const today = new Date();
        
        // Calcular la diferencia en días
        const diffTime = selectedDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        // Calcular minutos estimados basados en los días restantes
        let estimatedMinutes = 0;
        
        if (diffDays <= 0) {
            estimatedMinutes = 60;
        } else if (diffDays <= 1) {
            estimatedMinutes = 120;
        } else if (diffDays <= 3) {
            estimatedMinutes = 240;
        } else if (diffDays <= 7) {
            estimatedMinutes = 480;
        } else if (diffDays <= 14) {
            estimatedMinutes = 960;
        } else {
            estimatedMinutes = 1440;
        }
        
        minutesInput.value = estimatedMinutes;
        updateTimeDisplay();
    }
    
    // Event listeners
    dueDateInput.addEventListener('change', function() {
        calculateEstimatedTime(this.value);
    });
    
    if (dueDateInput.value) {
        calculateEstimatedTime(dueDateInput.value);
    }
    
    dueDateInput.addEventListener('focus', function() {
        this.showPicker && this.showPicker();
    });
});
</script>

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
    --section-bg: rgba(255, 255, 255, 0.02);
}

/* === ESTRUCTURA Y ESPACIADO === */
.container-fluid {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.card-custom {
    background-color: var(--bg-secondary);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.card-header-custom {
    background: linear-gradient(135deg, var(--bg-dark), var(--bg-secondary));
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem 2rem;
}

.card-body-custom {
    padding: 2rem;
}

/* === SECCIONES DEL FORMULARIO === */
.form-section {
    padding: 2rem;
    background: var(--section-bg);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.section-title {
    color: var(--accent-light);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--accent);
    display: flex;
    align-items: center;
}

/* === GRUPOS DE FORMULARIO === */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-light);
    font-size: 0.95rem;
}

/* === CONTROLES DE FORMULARIO === */
.form-control-custom {
    width: 100%;
    background-color: var(--bg-dark);
    border: 2px solid var(--border-color);
    color: var(--text-main);
    border-radius: 10px;
    padding: 1rem 1.25rem;
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
    box-shadow: 0 0 0 0.3rem rgba(126, 87, 194, 0.15);
    outline: none;
}

.form-select-custom {
    width: 100%;
    background-color: var(--bg-dark);
    border: 2px solid var(--border-color);
    color: var(--text-main);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
}

.form-select-custom:focus {
    background-color: var(--bg-dark);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.3rem rgba(126, 87, 194, 0.15);
    outline: none;
}

/* === VISUALIZACIÓN DE TIEMPO === */
.time-display-card {
    text-align: center;
}

.time-display-container {
    background: var(--bg-dark);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.time-display-container:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}

.time-display-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.time-display-label {
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* === BOTONES === */
.btn-back {
    background: transparent;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-back:hover {
    border-color: var(--accent);
    color: var(--accent-light);
    transform: translateX(-5px);
}

.btn-create {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-create:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(126, 87, 194, 0.4);
}

.btn-cancel {
    background: transparent;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-cancel:hover {
    background: var(--border-color);
    color: var(--text-light);
    transform: translateY(-2px);
}

/* === CONSEJOS === */
.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.tip-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    background: var(--section-bg);
    border-radius: 8px;
    border-left: 4px solid var(--success);
}

/* === MENSAJES DE ERROR === */
.error-message {
    color: var(--danger);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

/* === ÍCONOS DE FECHA === */
.form-control-custom[type="date"] {
    color-scheme: light !important;
}

.form-control-custom[type="date"]::-webkit-calendar-picker-indicator {
    filter: none !important;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    background-color: #ffca2c !important;
    margin-right: 4px;
    border: 2px solid #ffca2c;
    transition: all 0.3s ease;
}

.form-control-custom[type="date"]::-webkit-calendar-picker-indicator:hover {
    background-color: #ffdd70 !important;
    border-color: #ffdd70 !important;
    transform: scale(1.1);
}

/* === COLORES DE TEXTO === */
.text-main { color: var(--text-main); }
.text-secondary { color: var(--text-muted); }
.text-light { color: var(--text-light); }
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
.text-danger { color: var(--danger); }
.text-info { color: var(--info); }

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body-custom {
        padding: 1.5rem;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-create, .btn-cancel {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .card-header-custom {
        padding: 1.25rem 1.5rem;
    }
    
    .card-body-custom {
        padding: 1.25rem;
    }
    
    .form-section {
        padding: 1.25rem;
    }
    
    .time-display-value {
        font-size: 1.5rem;
    }
}
</style>
@endpush