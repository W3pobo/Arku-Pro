@extends('layouts.app')

@section('title', 'Nueva Etiqueta de Productividad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Nueva Etiqueta de Productividad</h1>

            <form action="{{ route('productivity-tags.store') }}" method="POST">
                @csrf

                <!-- Nombre y Tipo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Etiqueta *
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ej: Alta Concentración, Sin Distracciones...">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Etiqueta *
                        </label>
                        <select name="type" id="type" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona un tipo</option>
                            @foreach($tagTypes as $key => $value)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Color e Impacto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Color *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" id="color" value="{{ old('color', '#6b7280') }}"
                                   class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" name="color_hex" id="color_hex" 
                                   value="{{ old('color', '#6b7280') }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="impact_score" class="block text-sm font-medium text-gray-700 mb-2">
                            Puntuación de Impacto: <span id="impact_value">0</span>
                        </label>
                        <input type="range" name="impact_score" id="impact_score" 
                               min="-100" max="100" value="{{ old('impact_score', 0) }}"
                               class="w-full h-2 bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 rounded-lg appearance-none cursor-pointer slider"
                               oninput="updateImpactValue(this.value)">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Negativo (-100)</span>
                            <span>Neutral (0)</span>
                            <span>Positivo (+100)</span>
                        </div>
                        @error('impact_score')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción (Opcional)
                    </label>
                    <textarea name="description" id="description" 
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe cuándo y cómo usar esta etiqueta...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('productivity-tags.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-200">
                        Crear Etiqueta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateImpactValue(value) {
    const impactValue = document.getElementById('impact_value');
    impactValue.textContent = value > 0 ? '+' + value : value;
    impactValue.className = value > 0 ? 'text-green-600' : (value < 0 ? 'text-red-600' : 'text-gray-600');
}

// Sincronizar color picker
document.getElementById('color').addEventListener('input', function(e) {
    document.getElementById('color_hex').value = e.target.value;
});

document.getElementById('color_hex').addEventListener('input', function(e) {
    document.getElementById('color').value = e.target.value;
});

// Inicializar valor de impacto
updateImpactValue({{ old('impact_score', 0) }});
</script>

<style>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #ffffff;
    cursor: pointer;
    border: 2px solid #374151;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #ffffff;
    cursor: pointer;
    border: 2px solid #374151;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
}
</style>
@endsection