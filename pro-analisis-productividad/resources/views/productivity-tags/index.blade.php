@extends('layouts.app')

@section('title', 'Etiquetas de Productividad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold" style="color: #F0F2F5;">Etiquetas de Productividad</h1>
        <a href="{{ route('productivity-tags.create') }}" 
           class="px-6 py-3 rounded-lg transition-all duration-300 font-semibold border-0"
           style="background: linear-gradient(135deg, #7E57C2, #9575CD); color: #F0F2F5;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            Nueva Etiqueta
        </a>
    </div>

    @foreach($tagTypes as $typeKey => $typeName)
        @if(isset($tags[$typeKey]) && $tags[$typeKey]->count() > 0)
        <div class="rounded-lg shadow-lg p-6 mb-6 border-0" style="background-color: #2A3241;">
            <h2 class="text-xl font-semibold mb-4" style="color: #F0F2F5;">{{ $typeName }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($tags[$typeKey] as $tag)
                <div class="rounded-lg p-4 transition-all duration-300 border-0 hover:transform hover:scale-105"
                     style="background-color: #121826; border: 1px solid #3a4251;"
                     onmouseover="this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.3)'; this.style.borderColor='#7E57C2'"
                     onmouseout="this.style.boxShadow='none'; this.style.borderColor='#3a4251'">
                    <div class="flex justify-between items-start mb-3">
                        <span class="font-bold text-lg" style="color: {{ $tag->color }}">{{ $tag->name }}</span>
                        @if($tag->is_system)
                            <span class="px-3 py-1 text-xs rounded-full font-semibold"
                                  style="background-color: #7E57C2; color: #F0F2F5;">Sistema</span>
                        @endif
                    </div>
                    
                    @if($tag->description)
                        <p class="text-sm mb-4" style="color: #A9B4C7;">{{ $tag->description }}</p>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold px-3 py-1 rounded-full"
                              style="{{ $tag->impact_score > 0 ? 'background-color: rgba(34, 197, 94, 0.2); color: #22c55e' : ($tag->impact_score < 0 ? 'background-color: rgba(239, 68, 68, 0.2); color: #ef4444' : 'background-color: rgba(169, 180, 199, 0.2); color: #A9B4C7') }}">
                            Impacto: {{ $tag->impact_score > 0 ? '+' : '' }}{{ $tag->impact_score }}
                        </span>
                        
                        @if(!$tag->is_system)
                        <div class="flex space-x-3">
                            <a href="{{ route('productivity-tags.edit', $tag) }}" 
                               class="transition-all duration-300 font-semibold px-3 py-1 rounded-lg text-sm"
                               style="background-color: #2A3241; color: #7E57C2;"
                               onmouseover="this.style.backgroundColor='#7E57C2'; this.style.color='#F0F2F5'; this.style.transform='translateY(-1px)'"
                               onmouseout="this.style.backgroundColor='#2A3241'; this.style.color='#7E57C2'; this.style.transform='translateY(0)'">
                                Editar
                            </a>
                            <form action="{{ route('productivity-tags.destroy', $tag) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="transition-all duration-300 font-semibold px-3 py-1 rounded-lg text-sm"
                                        style="background-color: #2A3241; color: #ef4444;"
                                        onmouseover="this.style.backgroundColor='#ef4444'; this.style.color='#F0F2F5'; this.style.transform='translateY(-1px)'"
                                        onmouseout="this.style.backgroundColor='#2A3241'; this.style.color='#ef4444'; this.style.transform='translateY(0)'"
                                        onclick="return confirm('¿Estás seguro de eliminar esta etiqueta?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-sm px-3 py-1 rounded-lg"
                              style="background-color: rgba(169, 180, 199, 0.2); color: #A9B4C7;">
                            Solo lectura
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    <!-- Mensaje cuando no hay etiquetas -->
    @if($tags->isEmpty())
    <div class="rounded-lg shadow-lg p-8 text-center border-0" style="background-color: #2A3241;">
        <div class="mb-4">
            <i class="fas fa-tags text-4xl" style="color: #7E57C2;"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2" style="color: #F0F2F5;">No hay etiquetas creadas</h3>
        <p class="mb-4" style="color: #A9B4C7;">Comienza creando tu primera etiqueta de productividad</p>
        <a href="{{ route('productivity-tags.create') }}" 
           class="inline-flex items-center px-6 py-3 rounded-lg transition-all duration-300 font-semibold border-0"
           style="background: linear-gradient(135deg, #7E57C2, #9575CD); color: #F0F2F5;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <i class="fas fa-plus mr-2"></i>Crear Primera Etiqueta
        </a>
    </div>
    @endif
</div>

<style>
body {
    background-color: #121826 !important;
}

/* Smooth transitions for all interactive elements */
a, button, .transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
</style>
@endsection