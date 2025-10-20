@extends('layouts.app')

@section('title', 'Nuevo Registro de Tiempo')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Nuevo Registro de Tiempo</h1>
                <p class="page-subtitle">Completa la información sobre tu sesión de trabajo</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        Crear Nuevo Registro
                    </h3>
                </div>
                <div class="chart-body">
                    <form action="{{ route('time-trackings.store') }}" method="POST" class="form-custom">
                        @csrf

                        <!-- Sección 1: Información Básica -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h4 class="section-title">Información Básica</h4>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Proyecto -->
                                <div class="col-md-6">
                                    <label for="project_id" class="form-label">
                                        <i class="fas fa-project-diagram me-2"></i>Proyecto (Opcional)
                                    </label>
                                    <select name="project_id" id="project_id" 
                                            class="form-control form-control-dark">
                                        <option value="">Sin proyecto</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Categoría de Actividad -->
                                <div class="col-md-6">
                                    <label for="activity_category_id" class="form-label">
                                        <i class="fas fa-folder me-2"></i>Categoría de Actividad *
                                    </label>
                                    <select name="activity_category_id" id="activity_category_id" required
                                            class="form-control form-control-dark">
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('activity_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                                @if($category->is_system) 
                                                    <span class="badge-system">Sistema</span>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('activity_category_id')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="col-12">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-comment me-2"></i>Descripción de la Actividad *
                                    </label>
                                    <textarea name="description" id="description" required
                                              rows="3"
                                              class="form-control form-control-dark"
                                              placeholder="Describe qué hiciste durante este tiempo...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Tiempo -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4 class="section-title">Duración</h4>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Fecha y Hora de Inicio -->
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">
                                        <i class="fas fa-calendar me-2"></i>Fecha de Inicio *
                                    </label>
                                    <input type="date" name="start_date" id="start_date" required
                                           value="{{ old('start_date', date('Y-m-d')) }}"
                                           class="form-control form-control-dark">
                                    @error('start_date')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="start_time" class="form-label">
                                        <i class="fas fa-clock me-2"></i>Hora de Inicio *
                                    </label>
                                    <input type="time" name="start_time" id="start_time" required
                                           value="{{ old('start_time', '09:00') }}"
                                           class="form-control form-control-dark">
                                    @error('start_time')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Fecha y Hora de Fin -->
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">
                                        <i class="fas fa-calendar me-2"></i>Fecha de Fin *
                                    </label>
                                    <input type="date" name="end_date" id="end_date" required
                                           value="{{ old('end_date', date('Y-m-d')) }}"
                                           class="form-control form-control-dark">
                                    @error('end_date')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="end_time" class="form-label">
                                        <i class="fas fa-clock me-2"></i>Hora de Fin *
                                    </label>
                                    <input type="time" name="end_time" id="end_time" required
                                           value="{{ old('end_time', '10:00') }}"
                                           class="form-control form-control-dark">
                                    @error('end_time')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Duración Calculada -->
                                <div class="col-12">
                                    <div class="duration-card">
                                        <div class="duration-content">
                                            <i class="fas fa-hourglass-half duration-icon"></i>
                                            <div class="duration-info">
                                                <h5 class="duration-title">Duración Calculada</h5>
                                                <div id="duration-display" class="duration-value">1 hora 0 minutos</div>
                                                <small class="duration-note">La duración se calcula automáticamente</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Estado y Métricas -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h4 class="section-title">Estado y Métricas</h4>
                            </div>
                            
                            <!-- Niveles de Productividad -->
                            <div class="row g-4">
                                <!-- Nivel de Enfoque -->
                                <div class="col-md-6">
                                    <div class="metric-card">
                                        <div class="metric-header">
                                            <label for="focus_level" class="metric-title">
                                                <i class="fas fa-bullseye me-2"></i>Nivel de Enfoque
                                            </label>
                                            <span id="focus_level_value" class="metric-value">50%</span>
                                        </div>
                                        <input type="range" name="focus_level" id="focus_level" min="0" max="100" value="{{ old('focus_level', 50) }}"
                                               class="form-range custom-slider"
                                               oninput="updateSlider(this, 'focus_level_value')">
                                        <div class="metric-labels">
                                            <span class="metric-label">
                                                <div>Bajo</div>
                                                <small>0-33</small>
                                            </span>
                                            <span class="metric-label">
                                                <div>Medio</div>
                                                <small>34-66</small>
                                            </span>
                                            <span class="metric-label">
                                                <div>Alto</div>
                                                <small>67-100</small>
                                            </span>
                                        </div>
                                        @error('focus_level')
                                            <div class="form-error mt-3">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nivel de Energía -->
                                <div class="col-md-6">
                                    <div class="metric-card">
                                        <div class="metric-header">
                                            <label for="energy_level" class="metric-title">
                                                <i class="fas fa-bolt me-2"></i>Nivel de Energía
                                            </label>
                                            <span id="energy_level_value" class="metric-value">50%</span>
                                        </div>
                                        <input type="range" name="energy_level" id="energy_level" min="0" max="100" value="{{ old('energy_level', 50) }}"
                                               class="form-range custom-slider"
                                               oninput="updateSlider(this, 'energy_level_value')">
                                        <div class="metric-labels">
                                            <span class="metric-label">
                                                <div>Bajo</div>
                                                <small>0-33</small>
                                            </span>
                                            <span class="metric-label">
                                                <div>Medio</div>
                                                <small>34-66</small>
                                            </span>
                                            <span class="metric-label">
                                                <div>Alto</div>
                                                <small>67-100</small>
                                            </span>
                                        </div>
                                        @error('energy_level')
                                            <div class="form-error mt-3">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Notas Adicionales -->
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <h4 class="section-title">Información Adicional</h4>
                            </div>
                            
                            <div>
                                <label for="notes" class="form-label">
                                    <i class="fas fa-edit me-2"></i>Notas Adicionales (Opcional)
                                </label>
                                <textarea name="notes" id="notes" 
                                          rows="3"
                                          class="form-control form-control-dark"
                                          placeholder="Alguna observación adicional sobre tu sesión...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="form-actions">
                            <a href="{{ route('time-trackings.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Crear Registro
                            </button>
                        </div>
                    </form>
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

.chart-body {
    padding: 2rem;
}

/* === FORMULARIO === */
.form-custom {
    max-width: 100%;
}

.form-section {
    padding: 2rem;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.section-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-light);
    margin: 0;
}

.form-label {
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.form-control-dark {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-main);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control-dark:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25);
}

.form-control-dark:hover {
    border-color: var(--border-color);
}

/* === INPUTS DE FECHA Y HORA === */
.form-control-dark[type="date"],
.form-control-dark[type="time"] {
    color-scheme: dark;
}

.form-control-dark[type="date"]::-webkit-calendar-picker-indicator,
.form-control-dark[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0.8);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.form-control-dark[type="date"]::-webkit-calendar-picker-indicator:hover,
.form-control-dark[type="time"]::-webkit-calendar-picker-indicator:hover {
    background: rgba(126, 87, 194, 0.1);
}

/* === DURACIÓN CALCULADA === */
.duration-card {
    background: linear-gradient(135deg, rgba(126, 87, 194, 0.1), rgba(126, 87, 194, 0.05));
    border: 1px solid rgba(126, 87, 194, 0.3);
    border-radius: 12px;
    padding: 1.5rem;
}

.duration-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    text-align: center;
    justify-content: center;
}

.duration-icon {
    font-size: 2.5rem;
    color: var(--accent);
}

.duration-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.duration-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

.duration-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 0.25rem;
}

.duration-note {
    color: var(--text-muted);
    font-size: 0.85rem;
}

/* === MÉTRICAS CON SLIDERS === */
.metric-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.metric-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    margin: 0;
    display: flex;
    align-items: center;
}

.metric-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--accent);
    background: rgba(126, 87, 194, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: 1px solid rgba(126, 87, 194, 0.3);
}

.custom-slider {
    width: 100%;
    height: 8px;
    background: var(--border-color);
    border: none;
    border-radius: 10px;
    outline: none;
    margin-bottom: 1.5rem;
}

.custom-slider::-webkit-slider-thumb {
    appearance: none;
    height: 24px;
    width: 24px;
    border-radius: 50%;
    background: var(--accent);
    cursor: pointer;
    border: 3px solid var(--bg-secondary);
    box-shadow: 0 4px 12px rgba(126, 87, 194, 0.4);
    transition: all 0.2s ease;
}

.custom-slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(126, 87, 194, 0.6);
}

.metric-labels {
    display: flex;
    justify-content: space-between;
    text-align: center;
}

.metric-label {
    color: var(--text-muted);
    font-size: 0.85rem;
}

.metric-label div {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.metric-label small {
    color: var(--text-muted);
    opacity: 0.7;
}

/* === BADGES === */
.badge-system {
    background: var(--border-color);
    color: var(--text-main);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    margin-left: 0.5rem;
}

/* === ERRORES === */
.form-error {
    color: var(--warning);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

/* === BOTONES === */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--accent-light), var(--accent));
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    transform: translateY(-2px);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--border-color);
    color: var(--text-main);
    transform: translateY(-2px);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .chart-body {
        padding: 1rem;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .duration-content {
        flex-direction: column;
        text-align: center;
    }
    
    .metric-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
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
    
    .form-section {
        padding: 1rem;
    }
}
</style>

<script>
function updateSlider(slider, valueId) {
    const value = slider.value;
    document.getElementById(valueId).textContent = value + '%';
    
    // Update slider background
    const percentage = (value - slider.min) / (slider.max - slider.min) * 100;
    slider.style.background = `linear-gradient(to right, var(--accent) 0%, var(--accent) ${percentage}%, var(--border-color) ${percentage}%, var(--border-color) 100%)`;
}

function calculateDuration() {
    const startDate = document.getElementById('start_date').value;
    const startTime = document.getElementById('start_time').value;
    const endDate = document.getElementById('end_date').value;
    const endTime = document.getElementById('end_time').value;
    
    if (startDate && startTime && endDate && endTime) {
        const start = new Date(`${startDate}T${startTime}`);
        const end = new Date(`${endDate}T${endTime}`);
        
        if (start && end && end > start) {
            const diffMs = end - start;
            const diffMins = Math.floor(diffMs / 60000);
            const hours = Math.floor(diffMins / 60);
            const minutes = diffMins % 60;
            
            let durationText = '';
            if (hours > 0) {
                durationText += `${hours} hora${hours !== 1 ? 's' : ''} `;
            }
            if (minutes > 0 || hours === 0) {
                durationText += `${minutes} minuto${minutes !== 1 ? 's' : ''}`;
            }
            
            document.getElementById('duration-display').textContent = durationText || '0 minutos';
        } else {
            document.getElementById('duration-display').textContent = 'Fecha inválida';
            document.getElementById('duration-display').style.color = 'var(--warning)';
        }
    }
}

// Initialize sliders and duration calculator
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sliders
    const sliders = document.querySelectorAll('.custom-slider');
    sliders.forEach(slider => {
        updateSlider(slider, slider.id === 'focus_level' ? 'focus_level_value' : 'energy_level_value');
    });

    // Initialize duration calculator
    const timeInputs = document.querySelectorAll('#start_date, #start_time, #end_date, #end_time');
    timeInputs.forEach(input => {
        input.addEventListener('change', calculateDuration);
        input.addEventListener('input', calculateDuration);
    });

    // Calculate initial duration
    calculateDuration();
});
</script>
@endsection