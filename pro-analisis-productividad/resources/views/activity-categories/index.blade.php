@extends('layouts.app')

@section('title', 'Categorías de Actividad')

@section('content')
<div class="container mx-auto px-4 py-8" style="background-color: #121826; min-height: 100vh;">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold" style="color: #F0F2F5;">Categorías de Actividad</h1>
        <a href="{{ route('activity-categories.create') }}" 
           class="px-4 py-2 rounded-lg transition duration-300 button-primary">
            Nueva Categoría
        </a>
    </div>

    <div class="rounded-lg overflow-hidden" style="background-color: #2A3241;">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y" style="border-color: #3A4251;">
                <thead>
                    <tr style="background-color: #2A3241;">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #A9B4C7;">
                            Categoría
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #A9B4C7;">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #A9B4C7;">
                            Peso Productividad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #A9B4C7;">
                            Registros
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #A9B4C7;">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="background-color: #2A3241; border-color: #3A4251;">
                    @foreach($categories as $category)
                    <tr class="transition duration-300 hover:bg-gray-800">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">{{ $category->icon }}</span>
                                <span class="font-medium" style="color: {{ $category->color }};">
                                    {{ $category->name }}
                                </span>
                                @if($category->is_system)
                                    <span class="ml-2 px-2 py-1 text-xs rounded" style="background-color: #3A4251; color: #A9B4C7;">Sistema</span>
                                @endif
                            </div>
                            @if($category->description)
                                <p class="text-sm mt-1" style="color: #A9B4C7;">{{ $category->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->is_productive)
                                <span class="px-2 py-1 text-xs rounded" style="background-color: rgba(34, 197, 94, 0.2); color: #4ade80;">Productiva</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded" style="background-color: rgba(239, 68, 68, 0.2); color: #f87171;">No Productiva</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-24 rounded-full h-2.5 mr-2" style="background-color: #3A4251;">
                                    <div class="h-2.5 rounded-full" 
                                         style="width: {{ $category->productivity_weight }}%; background-color: #7E57C2;"></div>
                                </div>
                                <span class="text-sm" style="color: #F0F2F5;">{{ $category->productivity_weight }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #A9B4C7;">
                            {{ $category->timeTrackings->count() }} registros
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if(!$category->is_system)
                                <a href="{{ route('activity-categories.edit', $category) }}" 
                                   class="mr-3 transition duration-300 hover:text-violet-400" style="color: #7E57C2;">Editar</a>
                                <form action="{{ route('activity-categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="transition duration-300 hover:text-red-400"
                                            style="color: #ef4444;"
                                            onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                        Eliminar
                                    </button>
                                </form>
                            @else
                                <span style="color: #6B7280;">Solo lectura</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
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
</style>
@endsection