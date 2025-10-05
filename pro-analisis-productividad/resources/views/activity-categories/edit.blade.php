@extends('layouts.app')

@section('title', 'Editar Categoría de Actividad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Categoría: {{ $activityCategory->name }}</h1>

            <form action="{{ route('activity-categories.update', $activityCategory) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Los mismos campos que en create.blade.php pero con values -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría *
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $activityCategory->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Color *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" id="color" 
                                   value="{{ old('color', $activityCategory->color) }}"
                                   class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" name="color_hex" id="color_hex" 
                                   value="{{ old('color', $activityCategory->color) }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Icono (Opcional)
                        </label>
                        <input type="text" name="icon" id="icon"
                               value="{{ old('icon', $activityCategory->icon) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción (Opcional)
                    </label>
                    <textarea name="description" id="description" 
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $activityCategory->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Actividad
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_productive" value="1" 
                                   {{ old('is_productive', $activityCategory->is_productive) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Actividad Productiva</span>
                        </label>
                    </div>

                    <div>
                        <label for="productivity_weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Peso de Productividad *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="range" name="productivity_weight" id="productivity_weight" 
                                   min="0" max="100" value="{{ old('productivity_weight', $activityCategory->productivity_weight) }}"
                                   class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                   oninput="document.getElementById('weight_value').textContent = this.value + '%'">
                            <span id="weight_value" class="text-sm font-medium text-gray-700 w-12">
                                {{ old('productivity_weight', $activityCategory->productivity_weight) }}%
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('activity-categories.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-200">
                        Actualizar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Mismo script que en create.blade.php
document.getElementById('color').addEventListener('input', function(e) {
    document.getElementById('color_hex').value = e.target.value;
});

document.getElementById('color_hex').addEventListener('input', function(e) {
    document.getElementById('color').value = e.target.value;
});

const slider = document.getElementById('productivity_weight');
slider.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${slider.value}%, #e5e7eb ${slider.value}%, #e5e7eb 100%)`;
slider.addEventListener('input', function() {
    this.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${this.value}%, #e5e7eb ${this.value}%, #e5e7eb 100%)`;
});
</script>

<style>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
</style>
@endsection