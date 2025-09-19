<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Registro de Tiempo</title>
    {{-- Aquí podrías enlazar una hoja de estilos para que se vea mejor --}}
    <style>
        body { font-family: sans-serif; margin: 2em; }
        .form-group { margin-bottom: 1em; }
        label { display: block; margin-bottom: 0.5em; }
        input, select, textarea { width: 100%; padding: 0.5em; box-sizing: border-box; }
        .error-message { color: red; font-size: 0.8em; }
        .alert-danger { background-color: #f8d7da; color: #721c24; padding: 1em; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 1em; }
    </style>
</head>
<body>
    <h1>Crear Nuevo Registro de Tiempo</h1>

    {{-- Bloque para mostrar errores de validación generales --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups! Hubo algunos problemas con tu entrada.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- El formulario apunta a la ruta 'store' del controlador de recursos --}}
    <form action="{{ route('time-trackings.store') }}" method="POST">
        @csrf {{-- Token de seguridad OBLIGATORIO en Laravel --}}

        <div class="form-group">
            <label for="project_id">Proyecto</label>
            {{-- Este select se llenará con los proyectos del usuario --}}
            <select name="project_id" id="project_id" required>
                <option value="">Selecciona un proyecto</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="activity_type">Tipo de Actividad</label>
            <input type="text" name="activity_type" id="activity_type" value="{{ old('activity_type') }}" placeholder="Ej: Desarrollo, Diseño, Reunión" required>
            @error('activity_type')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">Descripción (Opcional)</label>
            <textarea name="description" id="description" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="start_time">Hora de Inicio</label>
            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
             @error('start_time')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_time">Hora de Fin</label>
            <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
             @error('end_time')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Guardar Registro</button>
    </form>
</body>
</html>