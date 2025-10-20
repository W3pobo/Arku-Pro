@extends('layouts.app')

@section('title', 'Crear Nuevo Proyecto')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header Mejorado -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-main mb-3">Crear Nuevo Proyecto</h1>
            <p class="text-secondary text-lg">Complete la información requerida para iniciar un nuevo proyecto</p>
        </div>

        <div class="card-custom">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <!-- Sección 1: Información Básica -->
                <div class="form-section mb-6">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Básica
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Nombre del Proyecto -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-heading me-2 text-accent"></i>Nombre del Proyecto *
                            </label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name') }}"
                                   class="input-custom"
                                   placeholder="Ingresa el nombre del proyecto">
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2 text-accent"></i>Descripción
                            </label>
                            <textarea name="description" id="description" 
                                      rows="3"
                                      class="input-custom"
                                      placeholder="Describe el propósito, objetivos y alcance del proyecto...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Configuración -->
                <div class="form-section mb-6">
                    <h3 class="section-title">
                        <i class="fas fa-cog me-2"></i>
                        Configuración del Proyecto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Estado -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-tasks me-2"></i>Estado *
                            </label>
                            <div class="custom-select-wrapper">
                                <select name="status" id="status" required class="custom-select">
                                    <option value="">Selecciona un estado</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🚀 Activo</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✅ Completado</option>
                                    <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>⏸️ Pausado</option>
                                </select>
                                <div class="custom-select-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            @error('status')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Color -->
                        <div class="form-group">
                            <label for="color" class="form-label">
                                <i class="fas fa-palette me-2"></i>Color del Proyecto
                            </label>
                            <div class="color-picker-container">
                                <input type="color" name="color" id="color"
                                       value="{{ old('color', '#7E57C2') }}"
                                       class="color-picker">
                                <div class="color-info">
                                    <span class="color-preview" style="background-color: {{ old('color', '#7E57C2') }}"></span>
                                    <span class="color-text">Haz clic para seleccionar un color</span>
                                </div>
                            </div>
                            @error('color')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Fechas -->
                <div class="form-section mb-6">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Cronograma
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fecha de Inicio -->
                        <div class="form-group">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-play-circle me-2"></i>Fecha de Inicio
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ old('start_date') }}"
                                   class="input-custom date-picker">
                            @error('start_date')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Fecha Límite -->
                        <div class="form-group">
                            <label for="deadline" class="form-label">
                                <i class="fas fa-flag-checkered me-2"></i>Fecha Límite
                            </label>
                            <input type="date" name="deadline" id="deadline"
                                   value="{{ old('deadline') }}"
                                   class="input-custom date-picker">
                            @error('deadline')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <div class="flex justify-between items-center pt-6 border-t border-gray-600">
                        <a href="{{ route('projects.index') }}" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn-create">
                            <i class="fas fa-plus-circle me-2"></i>Crear Proyecto
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Información de Ayuda -->
        <div class="card-custom mt-6">
            <div class="card-header-custom">
                <h6 class="card-title-custom mb-0 text-info">
                    <i class="fas fa-lightbulb me-2"></i>Consejos para Proyectos Exitosos
                </h6>
            </div>
            <div class="card-body-custom">
                <div class="tips-grid">
                    <div class="tip-item">
                        <i class="fas fa-check text-success me-2"></i>
                        <div>
                            <strong>Nombres descriptivos:</strong> 
                            <span class="text-light">Usa nombres claros que reflejen el propósito</span>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check text-success me-2"></i>
                        <div>
                            <strong>Estados apropiados:</strong> 
                            <span class="text-light">Nuevos proyectos suelen comenzar como "Activo"</span>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check text-success me-2"></i>
                        <div>
                            <strong>Fechas realistas:</strong> 
                            <span class="text-light">Establece plazos alcanzables</span>
                        </div>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-check text-success me-2"></i>
                        <div>
                            <strong>Colores distintivos:</strong> 
                            <span class="text-light">Usa colores para identificar proyectos fácilmente</span>
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
    --section-bg: rgba(255, 255, 255, 0.02);
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

/* === ESTRUCTURA PRINCIPAL === */
.card-custom {
    background-color: var(--bg-secondary);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    padding: 2.5rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    position: relative;
    overflow: hidden;
}

.card-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent), var(--accent-light), transparent);
}

/* === SECCIONES DEL FORMULARIO === */
.form-section {
    padding: 2rem;
    background: var(--section-bg);
    border-radius: 12px;
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.section-title {
    color: var(--accent-light);
    font-weight: 600;
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--accent);
    display: flex;
    align-items: center;
}

/* === GRUPOS DE FORMULARIO === */
.form-group {
    margin-bottom: 1rem;
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
    display: flex;
    align-items: center;
}

/* === CONTROLES DE FORMULARIO === */
.input-custom {
    width: 100%;
    background-color: var(--bg-dark);
    border: 2px solid var(--border-color);
    color: var(--text-main);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.input-custom::placeholder {
    color: var(--text-muted);
    opacity: 0.7;
}

.input-custom:focus {
    background-color: var(--bg-dark);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.3rem rgba(126, 87, 194, 0.15);
    outline: none;
}

.input-custom:hover {
    border-color: var(--accent-light);
    background-color: rgba(0, 0, 0, 0.2);
}

/* === SELECT PERSONALIZADO === */
.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select {
    width: 100%;
    background-color: var(--bg-dark);
    border: 2px solid var(--border-color);
    color: var(--text-main);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    padding-right: 3rem;
    font-size: 1rem;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    transition: all 0.3s ease;
}

.custom-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 0.3rem rgba(126, 87, 194, 0.15);
    background-color: var(--bg-dark);
}

.custom-select:hover {
    border-color: var(--accent-light);
    background-color: rgba(0, 0, 0, 0.2);
}

.custom-select-arrow {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
    transition: transform 0.3s ease;
}

.custom-select:focus + .custom-select-arrow {
    color: var(--accent);
    transform: translateY(-50%) rotate(180deg);
}

/* === COLOR PICKER MEJORADO === */
.color-picker-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem;
}

.color-picker {
    width: 60px;
    height: 60px;
    border: 3px solid var(--border-color);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-picker:hover {
    border-color: var(--accent);
    transform: scale(1.05);
}

.color-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.color-preview {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 2px solid var(--border-color);
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.color-text {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* === BOTONES PERSONALIZADOS === */
.btn-create {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: inline-flex;
    align-items: center;
    box-shadow: 
        0 4px 15px rgba(126, 87, 194, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.btn-create::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s;
}

.btn-create:hover::before {
    left: 100%;
}

.btn-create:hover {
    transform: translateY(-3px);
    box-shadow: 
        0 8px 25px rgba(126, 87, 194, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
    background: linear-gradient(135deg, var(--accent-light), var(--accent));
}

.btn-create:active {
    transform: translateY(-1px);
}

.btn-cancel {
    background: transparent;
    color: var(--text-muted);
    border: 2px solid var(--border-color);
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: inline-flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.btn-cancel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--border-color), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-cancel:hover::before {
    opacity: 0.1;
}

.btn-cancel:hover {
    transform: translateY(-3px);
    border-color: var(--accent);
    color: var(--accent-light);
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.2);
}

.btn-cancel:active {
    transform: translateY(-1px);
}

/* === CONSEJOS === */
.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

/* === ÍCONOS DE FECHA MEJORADOS === */
.input-custom[type="date"] {
    color-scheme: light !important;
}

.input-custom[type="date"]::-webkit-calendar-picker-indicator {
    filter: none !important;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    background-color: #ffca2c !important;
    margin-right: 4px;
    border: 2px solid #ffca2c;
    transition: all 0.3s ease;
}

.input-custom[type="date"]::-webkit-calendar-picker-indicator:hover {
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

/* === EFECTOS DE ANIMACIÓN === */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.btn-create, .btn-cancel {
    animation: float 6s ease-in-out infinite;
}

.btn-cancel {
    animation-delay: 0.2s;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-custom {
        padding: 1.5rem;
        border-radius: 12px;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions .flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-create, .btn-cancel {
        width: 100%;
        justify-content: center;
    }
    
    .color-picker-container {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .card-custom {
        padding: 1.25rem;
    }
    
    .form-section {
        padding: 1.25rem;
    }
    
    .grid.grid-cols-1.md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar preview de color en tiempo real
    const colorPicker = document.getElementById('color');
    const colorPreview = document.querySelector('.color-preview');
    
    colorPicker.addEventListener('input', function() {
        colorPreview.style.backgroundColor = this.value;
    });
    
    // Mejorar experiencia de selects
    const customSelects = document.querySelectorAll('.custom-select');
    
    customSelects.forEach(select => {
        select.addEventListener('focus', function() {
            this.parentElement.querySelector('.custom-select-arrow').style.color = 'var(--accent)';
        });
        
        select.addEventListener('blur', function() {
            this.parentElement.querySelector('.custom-select-arrow').style.color = 'var(--text-muted)';
        });
    });
    
    // Mejorar experiencia de date pickers
    const datePickers = document.querySelectorAll('.date-picker');
    
    datePickers.forEach(picker => {
        picker.addEventListener('focus', function() {
            this.showPicker && this.showPicker();
        });
    });
});
</script>
@endsection