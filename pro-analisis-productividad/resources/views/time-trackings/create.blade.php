@extends('layouts.app')

@section('title', 'Nuevo Registro de Tiempo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="mb-5">
                <h1 class="h2 fw-bold text-white mb-2">Nuevo Registro de Tiempo</h1>
                <p class="text-gray-400">Completa la información sobre tu sesión de trabajo</p>
            </div>

            <div class="card bg-gray-800 border-gray-700 shadow-lg">
                <div class="card-body p-4">
                    <form action="{{ route('time-trackings.store') }}" method="POST">
                        @csrf

                        <!-- Sección 1: Información Básica -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-gray-600">
                                <span class="bg-purple-600 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <span class="text-white fw-bold small">1</span>
                                </span>
                                <h2 class="h4 fw-semibold text-white mb-0">Información Básica</h2>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Proyecto -->
                                <div class="col-md-6">
                                    <label for="project_id" class="form-label text-gray-300">
                                        <i class="fas fa-project-diagram me-2"></i>Proyecto (Opcional)
                                    </label>
                                    <select name="project_id" id="project_id" 
                                            class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="" class="text-gray-400">Sin proyecto</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Categoría de Actividad -->
                                <div class="col-md-6">
                                    <label for="activity_category_id" class="form-label text-gray-300">
                                        <i class="fas fa-folder me-2"></i>Categoría de Actividad *
                                    </label>
                                    <select name="activity_category_id" id="activity_category_id" required
                                            class="form-select bg-gray-700 border-gray-600 text-white">
                                        <option value="" class="text-gray-400">Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('activity_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                                @if($category->is_system) 
                                                    <span class="badge bg-gray-600 ms-1">Sistema</span>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('activity_category_id')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="col-12">
                                    <label for="description" class="form-label text-gray-300">
                                        <i class="fas fa-comment me-2"></i>Descripción de la Actividad *
                                    </label>
                                    <textarea name="description" id="description" required
                                              rows="3"
                                              class="form-control bg-gray-700 border-gray-600 text-white"
                                              placeholder="Describe qué hiciste durante este tiempo...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Tiempo -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-gray-600">
                                <span class="bg-purple-600 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <span class="text-white fw-bold small">2</span>
                                </span>
                                <h2 class="h4 fw-semibold text-white mb-0">Duración</h2>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="start_time" class="form-label text-gray-300">
                                        <i class="fas fa-clock me-2"></i>Hora de Inicio *
                                    </label>
                                    <input type="datetime-local" name="start_time" id="start_time" required
                                           value="{{ old('start_time') }}"
                                           class="form-control bg-gray-700 border-gray-600 text-white">
                                    @error('start_time')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="end_time" class="form-label text-gray-300">
                                        <i class="fas fa-clock me-2"></i>Hora de Fin *
                                    </label>
                                    <input type="datetime-local" name="end_time" id="end_time" required
                                           value="{{ old('end_time') }}"
                                           class="form-control bg-gray-700 border-gray-600 text-white">
                                    @error('end_time')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Estado y Métricas -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-gray-600">
                                <span class="bg-purple-600 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <span class="text-white fw-bold small">3</span>
                                </span>
                                <h2 class="h4 fw-semibold text-white mb-0">Estado y Métricas</h2>
                            </div>
                            
                            <!-- Niveles de Productividad -->
                            <div class="row g-4 mb-4">
                                <!-- Nivel de Enfoque -->
                                <div class="col-md-6">
                                    <div class="card bg-gray-700 border-gray-600 h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <label for="focus_level" class="h5 fw-semibold text-white mb-0">
                                                    <i class="fas fa-bullseye me-2 text-purple-400"></i>Nivel de Enfoque
                                                </label>
                                                <span id="focus_level_value" class="h4 fw-bold text-purple-400 bg-purple-400/10 px-3 py-1 rounded">50%</span>
                                            </div>
                                            <input type="range" name="focus_level" id="focus_level" min="0" max="100" value="50"
                                                   class="form-range custom-slider mb-3"
                                                   oninput="updateSlider(this, 'focus_level_value')">
                                            <div class="d-flex justify-content-between text-gray-400 small">
                                                <span class="text-center">
                                                    <div>Bajo</div>
                                                </span>
                                                <span class="text-center">
                                                    <div>Medio</div>
                                                </span>
                                                <span class="text-center">
                                                    <div>Alto</div>
                                                </span>
                                            </div>
                                            @error('focus_level')
                                                <div class="text-danger small mt-3">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Nivel de Energía -->
                                <div class="col-md-6">
                                    <div class="card bg-gray-700 border-gray-600 h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <label for="energy_level" class="h5 fw-semibold text-white mb-0">
                                                    <i class="fas fa-bolt me-2 text-purple-400"></i>Nivel de Energía
                                                </label>
                                                <span id="energy_level_value" class="h4 fw-bold text-purple-400 bg-purple-400/10 px-3 py-1 rounded">50%</span>
                                            </div>
                                            <input type="range" name="energy_level" id="energy_level" min="0" max="100" value="50"
                                                   class="form-range custom-slider mb-3"
                                                   oninput="updateSlider(this, 'energy_level_value')">
                                            <div class="d-flex justify-content-between text-gray-400 small">
                                                <span class="text-center">
                                                    <div>Bajo</div>
                                                </span>
                                                <span class="text-center">
                                                    <div>Medio</div>
                                                </span>
                                                <span class="text-center">
                                                    <div>Alto</div>
                                                </span>
                                            </div>
                                            @error('energy_level')
                                                <div class="text-danger small mt-3">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Notas Adicionales -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-gray-600">
                                <span class="bg-purple-600 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <span class="text-white fw-bold small">4</span>
                                </span>
                                <h2 class="h4 fw-semibold text-white mb-0">Información Adicional</h2>
                            </div>
                            
                            <div>
                                <label for="notes" class="form-label text-gray-300">
                                    <i class="fas fa-sticky-note me-2"></i>Notas Adicionales (Opcional)
                                </label>
                                <textarea name="notes" id="notes" 
                                          rows="3"
                                          class="form-control bg-gray-700 border-gray-600 text-white"
                                          placeholder="Alguna observación adicional sobre tu sesión...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top border-gray-600">
                            <a href="{{ route('time-trackings.index') }}" 
                               class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary px-4 py-2">
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
.bg-gray-800 { background-color: var(--bg-secondary) !important; }
.bg-gray-700 { background-color: #374151 !important; }
.bg-gray-600 { background-color: #4B5563 !important; }
.border-gray-700 { border-color: #374151 !important; }
.border-gray-600 { border-color: #4B5563 !important; }
.text-gray-400 { color: #9CA3AF !important; }
.text-purple-400 { color: #A78BFA !important; }
.bg-purple-400\/10 { background-color: rgba(167, 139, 250, 0.1) !important; }

.custom-slider {
    height: 6px;
    background: #4B5563;
    border: none;
    border-radius: 10px;
}

.custom-slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: var(--accent);
    cursor: pointer;
    border: 3px solid var(--bg-primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.custom-slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: var(--accent);
    cursor: pointer;
    border: 3px solid var(--bg-primary);
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}
</style>

<script>
function updateSlider(slider, valueId) {
    const value = slider.value;
    document.getElementById(valueId).textContent = value + '%';
    
    // Update slider background
    const percentage = (value - slider.min) / (slider.max - slider.min) * 100;
    slider.style.background = `linear-gradient(to right, var(--accent) 0%, var(--accent) ${percentage}%, #4B5563 ${percentage}%, #4B5563 100%)`;
}

// Initialize sliders
document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.custom-slider');
    sliders.forEach(slider => {
        updateSlider(slider, slider.id === 'focus_level' ? 'focus_level_value' : 'energy_level_value');
    });
});
</script>
@endsection