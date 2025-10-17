@extends('layouts.app')

@section('title', 'Editar Categoría de Actividad')

@section('content')
<div class="container mx-auto px-4 py-8" style="background-color: #121826; min-height: 100vh;">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-lg p-6" style="background-color: #2A3241;">
            <h1 class="text-2xl font-bold mb-6" style="color: #F0F2F5;">Editar Categoría: {{ $activityCategory->name }}</h1>

            <form action="{{ route('activity-categories.update', $activityCategory) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Los mismos campos que en create.blade.php pero con values -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                        Nombre de la Categoría *
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $activityCategory->name) }}"
                           class="w-full rounded-lg px-4 py-2 focus:ring-2 transition duration-300"
                           style="background-color: #121826; border: 1px solid #2A3241; color: #F0F2F5;">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="color" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Color *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" id="color" 
                                   value="{{ old('color', $activityCategory->color) }}"
                                   class="w-16 h-10 rounded-lg cursor-pointer transition-transform duration-300 hover:scale-105">
                            <input type="text" name="color_hex" id="color_hex" 
                                   value="{{ old('color', $activityCategory->color) }}"
                                   class="flex-1 rounded-lg px-4 py-2 focus:ring-2 transition duration-300"
                                   style="background-color: #121826; border: 1px solid #2A3241; color: #F0F2F5;">
                        </div>
                        @error('color')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Icono (Opcional)
                        </label>
                        <input type="text" name="icon" id="icon"
                               value="{{ old('icon', $activityCategory->icon) }}"
                               class="w-full rounded-lg px-4 py-2 focus:ring-2 transition duration-300"
                               style="background-color: #121826; border: 1px solid #2A3241; color: #F0F2F5;">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                        Descripción (Opcional)
                    </label>
                    <textarea name="description" id="description" 
                              rows="3"
                              class="w-full rounded-lg px-4 py-2 focus:ring-2 transition duration-300"
                              style="background-color: #121826; border: 1px solid #2A3241; color: #F0F2F5;">{{ old('description', $activityCategory->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Tipo de Actividad
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_productive" value="1" 
                                   {{ old('is_productive', $activityCategory->is_productive) ? 'checked' : '' }}
                                   class="rounded text-violet-600 focus:ring-violet-500 transition duration-300">
                            <span class="ml-2 text-sm" style="color: #F0F2F5;">Actividad Productiva</span>
                        </label>
                    </div>

                    <div>
                        <label for="productivity_weight" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Peso de Productividad *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="range" name="productivity_weight" id="productivity_weight" 
                                   min="0" max="100" value="{{ old('productivity_weight', $activityCategory->productivity_weight) }}"
                                   class="flex-1 h-2 rounded-lg appearance-none cursor-pointer slider transition duration-300"
                                   oninput="document.getElementById('weight_value').textContent = this.value + '%'">
                            <span id="weight_value" class="text-sm font-medium w-12" style="color: #F0F2F5;">
                                {{ old('productivity_weight', $activityCategory->productivity_weight) }}%
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('activity-categories.index') }}" 
                       class="px-6 py-2 rounded-lg transition duration-300 button-secondary">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 rounded-lg transition duration-300 button-primary">
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
slider.style.background = `linear-gradient(to right, #7E57C2 0%, #7E57C2 ${slider.value}%, #2A3241 ${slider.value}%, #2A3241 100%)`;
slider.addEventListener('input', function() {
    this.style.background = `linear-gradient(to right, #7E57C2 0%, #7E57C2 ${this.value}%, #2A3241 ${this.value}%, #2A3241 100%)`;
});
</script>

<style>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #7E57C2;
    cursor: pointer;
    border: 2px solid #F0F2F5;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

.slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(126, 87, 194, 0.5);
}

.button-primary {
    background-color: #7E57C2;
    color: #F0F2F5;
    box-shadow: 0 4px 6px rgba(126, 87, 194, 0.2);
}

.button-primary:hover {
    background-color: #6d46b8;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(126, 87, 194, 0.3);
}

.button-secondary {
    border: 1px solid #2A3241;
    color: #A9B4C7;
    background-color: #2A3241;
}

.button-secondary:hover {
    background-color: #3A4251;
    color: #F0F2F5;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

input:focus, textarea:focus {
    outline: none;
    border-color: #7E57C2 !important;
    box-shadow: 0 0 0 2px rgba(126, 87, 194, 0.2) !important;
}
</style>
@endsection