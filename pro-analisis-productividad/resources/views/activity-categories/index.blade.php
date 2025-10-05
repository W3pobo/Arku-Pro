@extends('layouts.app')

@section('title', 'Categorías de Actividad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Categorías de Actividad</h1>
        <a href="{{ route('activity-categories.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            Nueva Categoría
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Peso Productividad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registros
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">{{ $category->icon }}</span>
                                <span class="font-medium text-gray-900" style="color: {{ $category->color }}">
                                    {{ $category->name }}
                                </span>
                                @if($category->is_system)
                                    <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Sistema</span>
                                @endif
                            </div>
                            @if($category->description)
                                <p class="text-sm text-gray-500 mt-1">{{ $category->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->is_productive)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Productiva</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">No Productiva</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" 
                                         style="width: {{ $category->productivity_weight }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $category->productivity_weight }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $category->timeTrackings->count() }} registros
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if(!$category->is_system)
                                <a href="{{ route('activity-categories.edit', $category) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('activity-categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                        Eliminar
                                    </button>
                                </form>
                   |         @else
                                <span class="text-gray-400">Solo lectura</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection