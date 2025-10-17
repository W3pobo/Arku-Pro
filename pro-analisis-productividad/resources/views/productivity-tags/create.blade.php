@extends('layouts.app')

@section('title', 'Nueva Etiqueta de Productividad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-lg shadow-lg p-6 border-0" style="background-color: #2A3241;">
            <h1 class="text-2xl font-bold mb-6" style="color: #F0F2F5;">Nueva Etiqueta de Productividad</h1>

            <form action="{{ route('productivity-tags.store') }}" method="POST">
                @csrf

                <!-- Nombre y Tipo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Nombre de la Etiqueta *
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name') }}"
                               class="w-full rounded-lg px-4 py-2 border-0 transition-all duration-300"
                               style="background-color: #121826; color: #F0F2F5;"
                               placeholder="Ej: Alta Concentración, Sin Distracciones..."
                               onfocus="this.style.backgroundColor='#1a2233'; this.style.boxShadow='0 0 0 0.2rem rgba(126, 87, 194, 0.25)'"
                               onblur="this.style.backgroundColor='#121826'; this.style.boxShadow='none'">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Tipo de Etiqueta *
                        </label>
                        <select name="type" id="type" required
                                class="w-full rounded-lg px-4 py-2 border-0 transition-all duration-300"
                                style="background-color: #121826; color: #F0F2F5;"
                                onfocus="this.style.backgroundColor='#1a2233'; this.style.boxShadow='0 0 0 0.2rem rgba(126, 87, 194, 0.25)'"
                                onblur="this.style.backgroundColor='#121826'; this.style.boxShadow='none'">
                            <option value="">Selecciona un tipo</option>
                            @foreach($tagTypes as $key => $value)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Color e Impacto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="color" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Color *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" id="color" value="{{ old('color', '#7E57C2') }}"
                                   class="w-16 h-10 rounded-lg cursor-pointer border-0">
                            <input type="text" name="color_hex" id="color_hex" 
                                   value="{{ old('color', '#7E57C2') }}"
                                   class="flex-1 rounded-lg px-4 py-2 border-0 transition-all duration-300"
                                   style="background-color: #121826; color: #F0F2F5;"
                                   onfocus="this.style.backgroundColor='#1a2233'; this.style.boxShadow='0 0 0 0.2rem rgba(126, 87, 194, 0.25)'"
                                   onblur="this.style.backgroundColor='#121826'; this.style.boxShadow='none'">
                        </div>
                        @error('color')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="impact_score" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                            Puntuación de Impacto: <span id="impact_value" class="font-bold">0</span>
                        </label>
                        <input type="range" name="impact_score" id="impact_score" 
                               min="-100" max="100" value="{{ old('impact_score', 0) }}"
                               class="w-full h-2 rounded-lg appearance-none cursor-pointer slider"
                               style="background: linear-gradient(to right, #ef4444, #eab308, #22c55e);"
                               oninput="updateImpactValue(this.value)">
                        <div class="flex justify-between text-xs mt-1">
                            <span style="color: #A9B4C7;">Negativo (-100)</span>
                            <span style="color: #A9B4C7;">Neutral (0)</span>
                            <span style="color: #A9B4C7;">Positivo (+100)</span>
                        </div>
                        @error('impact_score')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium mb-2" style="color: #F0F2F5;">
                        Descripción (Opcional)
                    </label>
                    <textarea name="description" id="description" 
                              rows="3"
                              class="w-full rounded-lg px-4 py-2 border-0 transition-all duration-300"
                              style="background-color: #121826; color: #F0F2F5;"
                              placeholder="Describe cuándo y cómo usar esta etiqueta..."
                              onfocus="this.style.backgroundColor='#1a2233'; this.style.boxShadow='0 0 0 0.2rem rgba(126, 87, 194, 0.25)'"
                              onblur="this.style.backgroundColor='#121826'; this.style.boxShadow='none'">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('productivity-tags.index') }}" 
                       class="px-6 py-2 rounded-lg transition-all duration-300 border-0 font-semibold"
                       style="background-color: #2A3241; color: #A9B4C7; border: 1px solid #3a4251 !important;"
                       onmouseover="this.style.backgroundColor='#3a4251'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.2)'"
                       onmouseout="this.style.backgroundColor='#2A3241'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 rounded-lg transition-all duration-300 font-semibold border-0"
                            style="background: linear-gradient(135deg, #7E57C2, #9575CD); color: #F0F2F5;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
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
    impactValue.style.color = value > 0 ? '#22c55e' : (value < 0 ? '#ef4444' : '#A9B4C7');
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
    background: #F0F2F5;
    cursor: pointer;
    border: 2px solid #7E57C2;
    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    transition: all 0.3s ease;
}

.slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(126, 87, 194, 0.6);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #F0F2F5;
    cursor: pointer;
    border: 2px solid #7E57C2;
    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    transition: all 0.3s ease;
}

.slider::-moz-range-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(126, 87, 194, 0.6);
}

body {
    background-color: #121826 !important;
}
</style>
@endsection