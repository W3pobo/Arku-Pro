@extends('layouts.app')

@section('title', 'Editar Proyecto')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Editar Proyecto</h1>
                <p class="page-subtitle">Actualiza la información de tu proyecto</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-edit me-2"></i>
                        Editar Proyecto
                    </h3>
                </div>
                <div class="chart-body">
                    <form action="{{ route('projects.update', $project) }}" method="POST" class="form-custom">
                        @csrf
                        @method('PUT')

                        <!-- Sección 1: Información Básica -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h4 class="section-title">Información Básica</h4>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Nombre del Proyecto -->
                                <div class="col-12">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-tag me-2"></i>Nombre del Proyecto *
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           value="{{ old('name', $project->name) }}"
                                           class="form-control form-control-dark"
                                           placeholder="Ingresa el nombre del proyecto">
                                    @error('name')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="col-12">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-comment me-2"></i>Descripción
                                    </label>
                                    <textarea name="description" id="description" 
                                              rows="4"
                                              class="form-control form-control-dark"
                                              placeholder="Describe el proyecto...">{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Estado y Fechas -->
                        <div class="form-section mb-5">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h4 class="section-title">Estado y Fechas</h4>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Estado -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-chart-line me-2"></i>Estado *
                                    </label>
                                    <select name="status" id="status" required class="form-control form-control-dark">
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                                        <option value="paused" {{ old('status', $project->status) == 'paused' ? 'selected' : '' }}>Pausado</option>
                                        <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    @error('status')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Color -->
                                <div class="col-md-6">
                                    <label for="color" class="form-label">
                                        <i class="fas fa-palette me-2"></i>Color del Proyecto
                                    </label>
                                    <div class="color-input-wrapper">
                                        <input type="color" name="color" id="color"
                                               value="{{ old('color', $project->color ?? '#7E57C2') }}"
                                               class="form-control-color">
                                        <span class="color-note">Haz clic para seleccionar un color</span>
                                    </div>
                                    @error('color')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Fecha de Inicio -->
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">
                                        <i class="fas fa-play-circle me-2"></i>Fecha de Inicio
                                    </label>
                                    <input type="date" name="start_date" id="start_date"
                                           value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}"
                                           class="form-control form-control-dark">
                                    @error('start_date')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Fecha Límite -->
                                <div class="col-md-6">
                                    <label for="deadline" class="form-label">
                                        <i class="fas fa-flag-checkered me-2"></i>Fecha Límite
                                    </label>
                                    <input type="date" name="deadline" id="deadline"
                                           value="{{ old('deadline', $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('Y-m-d') : '') }}"
                                           class="form-control form-control-dark">
                                    @error('deadline')
                                        <div class="form-error">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Metas y Objetivos -->
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4 class="section-title">Metas y Objetivos</h4>
                            </div>
                            
                            <div>
                                <label for="goals" class="form-label">
                                    <i class="fas fa-tasks me-2"></i>Metas u Objetivos
                                </label>
                                <textarea name="goals" id="goals" 
                                          rows="3"
                                          class="form-control form-control-dark"
                                          placeholder="Objetivos específicos del proyecto...">{{ old('goals', $project->goals) }}</textarea>
                                @error('goals')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="form-actions">
                            <a href="{{ route('projects.index') }}" 
                               class="btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" 
                                    class="btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sección para eliminar proyecto -->
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
                            <p>Esta acción no se puede deshacer. Se eliminarán todos los datos asociados al proyecto.</p>
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
    margin-bottom: 1.5rem;
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
    width: 100%;
}

.form-control-dark:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25);
    outline: none;
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

/* === INPUT DE COLOR === */
.color-input-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-control-color {
    width: 60px !important;
    height: 60px !important;
    border: 2px solid var(--border-color) !important;
    border-radius: 12px !important;
    cursor: pointer;
    background: var(--bg-secondary) !important;
}

.form-control-color:hover {
    border-color: var(--accent) !important;
    transform: scale(1.05);
}

.color-note {
    color: var(--text-muted);
    font-size: 0.85rem;
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
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border: 2px solid var(--border-color);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-secondary:hover {
    background: var(--border-color);
    color: var(--text-main);
    transform: translateY(-2px);
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
    
    .color-input-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
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
// Script para mejorar la experiencia del input de color
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    
    if (colorInput) {
        colorInput.addEventListener('change', function() {
            // Efecto visual cuando se cambia el color
            this.style.transform = 'scale(1.1)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
        
        colorInput.addEventListener('mouseenter', function() {
            this.style.borderColor = 'var(--accent)';
        });
        
        colorInput.addEventListener('mouseleave', function() {
            this.style.borderColor = 'var(--border-color)';
        });
    }
});
</script>
@endsection