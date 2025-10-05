@extends('layouts.app')

@section('title', 'Etiquetas de Productividad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Etiquetas de Productividad</h1>
        <a href="{{ route('productivity-tags.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            Nueva Etiqueta
        </a>
    </div>

    @foreach($tagTypes as $typeKey => $typeName)
        @if(isset($tags[$typeKey]) && $tags[$typeKey]->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $typeName }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($tags[$typeKey] as $tag)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-medium" style="color: {{ $tag->color }}">{{ $tag->name }}</span>
                        @if($tag->is_system)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Sistema</span>
                        @endif
                    </div>
                    
                    @if($tag->description)
                        <p class="text-sm text-gray-600 mb-3">{{ $tag->description }}</p>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm {{ $tag->impact_score > 0 ? 'text-green-600' : ($tag->impact_score < 0 ? 'text-red-600' : 'text-gray-600') }}">
                            Impacto: {{ $tag->impact_score > 0 ? '+' : '' }}{{ $tag->impact_score }}
                        </span>
                        
                        @if(!$tag->is_system)
                        <div class="flex space-x-2">
                            <a href="{{ route('productivity-tags.edit', $tag) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">Editar</a>
                            <form action="{{ route('productivity-tags.destroy', $tag) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar esta etiqueta?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-400 text-sm">Solo lectura</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
</div>
@endsection