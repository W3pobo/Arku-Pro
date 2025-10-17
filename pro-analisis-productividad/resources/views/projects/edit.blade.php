@extends('layouts.app')

@section('title', 'Editar Proyecto')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="card-custom">
            <h1 class="text-2xl font-bold text-main mb-6">Editar Proyecto</h1>

            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre del Proyecto -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-main mb-2">
                        Nombre del Proyecto *
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $project->name) }}"
                           class="input-custom"
                           placeholder="Ingresa el nombre del proyecto">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-main mb-2">
                        Descripción
                    </label>
                    <textarea name="description" id="description" 
                              rows="4"
                              class="input-custom"
                              placeholder="Describe el proyecto...">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-main mb-2">
                        Estado *
                    </label>
                    <select name="status" id="status" required class="input-custom">
                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="paused" {{ old('status', $project->status) == 'paused' ? 'selected' : '' }}>Pausado</option>
                        <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Fecha de Inicio -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-main mb-2">
                            Fecha de Inicio
                        </label>
                        <input type="date" name="start_date" id="start_date"
                               value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}"
                               class="input-custom">
                        @error('start_date')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha Límite -->
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-main mb-2">
                            Fecha Límite
                        </label>
                        <input type="date" name="deadline" id="deadline"
                               value="{{ old('deadline', $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('Y-m-d') : '') }}"
                               class="input-custom">
                        @error('deadline')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Color -->
                <div class="mb-6">
                    <label for="color" class="block text-sm font-medium text-main mb-2">
                        Color del Proyecto
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="color" name="color" id="color"
                               value="{{ old('color', $project->color ?? '#7E57C2') }}"
                               class="w-16 h-16 border-2 border-gray-600 rounded-lg cursor-pointer bg-secondary">
                        <span class="text-secondary text-sm">
                            Haz clic para seleccionar un color
                        </span>
                    </div>
                    @error('color')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metas u Objetivos -->
                <div class="mb-6">
                    <label for="goals" class="block text-sm font-medium text-main mb-2">
                        Metas u Objetivos
                    </label>
                    <textarea name="goals" id="goals" 
                              rows="3"
                              class="input-custom"
                              placeholder="Objetivos específicos del proyecto...">{{ old('goals', $project->goals) }}</textarea>
                    @error('goals')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-600">
                    <a href="{{ route('projects.index') }}" 
                       class="btn-secondary px-6 py-3 rounded-lg transition duration-200 transform hover:-translate-y-1">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="btn-primary px-6 py-3 rounded-lg transition duration-200 transform hover:-translate-y-1">
                        Actualizar Proyecto
                    </button>
                </div>
            </form>

            <!-- Sección para eliminar proyecto -->
            <div class="mt-8 pt-6 border-t border-red-400 border-opacity-30">
                <h3 class="text-lg font-semibold text-red-400 mb-4">Zona Peligrosa</h3>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn-danger px-6 py-3 rounded-lg transition duration-200 transform hover:-translate-y-1">
                        <i class="fas fa-trash mr-2"></i>Eliminar Proyecto
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --bg-main: #121826;
    --bg-secondary: #2A3241;
    --accent: #7E57C2;
    --text-main: #F0F2F5;
    --text-secondary: #A9B4C7;
    --hover-accent: #6a4da2;
    --border-color: #3a4458;
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

.card-custom {
    background-color: var(--bg-secondary);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.card-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), transparent);
}

.input-custom {
    width: 100%;
    background-color: var(--bg-main);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    color: var(--text-main);
    transition: all 0.3s ease;
}

.input-custom:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(126, 87, 194, 0.2);
}

.input-custom:hover {
    border-color: var(--hover-accent);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--hover-accent));
    color: white;
    border: none;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-primary:hover {
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    background: linear-gradient(135deg, var(--hover-accent), var(--accent));
}

.btn-secondary {
    background-color: transparent;
    border: 2px solid var(--text-secondary);
    color: var(--text-secondary);
    font-weight: 600;
}

.btn-secondary:hover {
    background-color: var(--accent);
    border-color: var(--accent);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    background: linear-gradient(135deg, #dc2626, #ef4444);
}

.text-main { color: var(--text-main); }
.text-secondary { color: var(--text-secondary); }
</style>
@endsection