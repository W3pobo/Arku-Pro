@extends('layouts.app')

@section('title', 'Nuevo Registro de Tiempo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Nuevo Registro de Tiempo</h1>

            <form action="{{ route('time-trackings.store') }}" method="POST">
                @csrf

                <!-- Proyecto -->
                <div class="mb-6">
                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Proyecto (Opcional)
                    </label>
                    <select name="project_id" id="project_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sin proyecto</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría de Actividad -->
                <div class="mb-6">
                    <label for="activity_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría de Actividad *
                    </label>
                    <select name="activity_category_id" id="activity_category_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('activity_category_id') == $category->id ? 'selected' : '' }}
                                    data-color="{{ $category->color }}"
                                    style="color: {{ $category->color }}">
                                {{ $category->icon }} {{ $category->name }}
                                @if($category->is_system) (Sistema) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('activity_category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción de la Actividad *
                    </label>
                    <textarea name="description" id="description" required
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe qué hiciste durante este tiempo...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tiempo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Hora de Inicio *
                        </label>
                        <input type="datetime-local" name="start_time" id="start_time" required
                               value="{{ old('start_time') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Hora de Fin *
                        </label>
                        <input type="datetime-local" name="end_time" id="end_time" required
                               value="{{ old('end_time') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Niveles de Productividad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Nivel de Enfoque -->
                    <div>
                        <label for="focus_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Nivel de Enfoque: <span id="focus_level_value">50</span>%
                        </label>
                        <input type="range" name="focus_level" id="focus_level" min="0" max="100" value="50"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                               oninput="document.getElementById('focus_level_value').textContent = this.value">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Bajo</span>
                            <span>Medio</span>
                            <span>Alto</span>
                        </div>
                        @error('focus_level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nivel de Energía -->
                    <div>
                        <label for="energy_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Nivel de Energía: <span id="energy_level_value">50</span>%
                        </label>
                        <input type="range" name="energy_level" id="energy_level" min="0" max="100" value="50"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                               oninput="document.getElementById('energy_level_value').textContent = this.value">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Bajo</span>
                            <span>Medio</span>
                            <span>Alto</span>
                        </div>
                        @error('energy_level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Etiquetas de Productividad -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Etiquetas de Productividad
                    </label>
                    
                    @foreach($tagTypes as $typeKey => $typeName)
                    <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-2">{{ $typeName }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @php
                                $typeTags = \App\Models\ProductivityTag::where('type', $typeKey)
                                    ->where(function($query) {
                                        $query->where('user_id', auth()->id())
                                              ->orWhere('is_system', true);
                                    })
                                    ->get();
                            @endphp
                            
                            @foreach($typeTags as $tag)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="productivity_tags[]" value="{{ $tag->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       {{ in_array($tag->id, old('productivity_tags', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm" style="color: {{ $tag->color }}">{{ $tag->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Notas -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notas Adicionales (Opcional)
                    </label>
                    <textarea name="notes" id="notes" 
                              rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Alguna observación adicional...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('time-trackings.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-200">
                        Crear Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    border: none;
}
</style>
@endsection