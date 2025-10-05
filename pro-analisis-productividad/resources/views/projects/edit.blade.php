@extends('layouts.app')

@section('title', 'Editar Proyecto')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <div class="card" style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin-bottom: 1.5rem;">Editar Proyecto</h1>

        <form action="{{ route('projects.update', $project) }}" method="POST" style="width: 100%;">
            @csrf
            @method('PUT')

            <!-- Nombre del Proyecto -->
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Nombre del Proyecto *
                </label>
                <input type="text" name="name" id="name" required
                       value="{{ old('name', $project->name) }}"
                       style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 1rem; 
                              transition: all 0.2s; outline: none;"
                       onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                       onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                       placeholder="Ingresa el nombre del proyecto">
                @error('name')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div style="margin-bottom: 1.5rem;">
                <label for="description" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Descripción
                </label>
                <textarea name="description" id="description" 
                          rows="4"
                          style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 1rem; 
                                 transition: all 0.2s; outline: none; resize: vertical; min-height: 100px;"
                          onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                          onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                          placeholder="Describe el proyecto...">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div style="margin-bottom: 1.5rem;">
                <label for="status" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Estado *
                </label>
                <select name="status" id="status" required
                        style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 1rem; 
                               background: white; transition: all 0.2s; outline: none;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                    <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="paused" {{ old('status', $project->status) == 'paused' ? 'selected' : '' }}>Pausado</option>
                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('status')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fechas -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <!-- Fecha de Inicio -->
                <div>
                    <label for="start_date" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Fecha de Inicio
                    </label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}"
                           style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 1rem; 
                                  transition: all 0.2s; outline: none;"
                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                           onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                    @error('start_date')
                        <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha Límite -->
                <div>
                    <label for="deadline" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Fecha Límite
                    </label>
                    <input type="date" name="deadline" id="deadline"
                           value="{{ old('deadline', $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('Y-m-d') : '') }}"
                           style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 1rem; 
                                  transition: all 0.2s; outline: none;"
                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                           onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
                    @error('deadline')
                        <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Color -->
            <div style="margin-bottom: 1.5rem;">
                <label for="color" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Color del Proyecto
                </label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <input type="color" name="color" id="color"
                           value="{{ old('color', $project->color ?? '#3b82f6') }}"
                           style="width: 60px; height: 60px; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                    <span style="color: #6b7280; font-size: 0.875rem;">
                        Haz clic para seleccionar un color
                    </span>
                </div>
                @error('color')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Metas u Objetivos -->
            <div style="margin-bottom: 2rem;">
                <label for="goals" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                    Metas u Objetivos
                </label>
                <textarea name="goals" id="goals" 
                          rows="3"
                          style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 1rem; 
                                 transition: all 0.2s; outline: none; resize: vertical; min-height: 80px;"
                          onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                          onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                          placeholder="Objetivos específicos del proyecto...">{{ old('goals', $project->goals) }}</textarea>
                @error('goals')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div style="display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <a href="{{ route('projects.index') }}" 
                   style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; border: 1px solid #d1d5db; 
                          border-radius: 0.5rem; color: #374151; text-decoration: none; font-weight: 500;
                          transition: all 0.2s; background: white;">
                    Cancelar
                </a>
                <button type="submit" 
                        style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; border: none; 
                               border-radius: 0.5rem; background: #3b82f6; color: white; font-weight: 500;
                               transition: all 0.2s; cursor: pointer;">
                    Actualizar Proyecto
                </button>
            </div>
        </form>

        <!-- Sección para eliminar proyecto -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Zona Peligrosa</h3>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; border: none; 
                               border-radius: 0.5rem; background: #ef4444; color: white; font-weight: 500;
                               transition: all 0.2s; cursor: pointer;">
                    Eliminar Proyecto
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Estilos adicionales para hover states */
a:hover {
    background-color: #f9fafb !important;
}

button[type="submit"]:hover {
    background-color: #2563eb !important;
}

button[style*="background: #ef4444"]:hover {
    background-color: #dc2626 !important;
}

input:hover, select:hover, textarea:hover {
    border-color: #9ca3af !important;
}
</style>
@endsection