@extends('layouts.app')

@section('title', 'Ver Registro de Tiempo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Detalles del Registro de Tiempo</h1>
            
            <div class="space-y-4">
                <div>
                    <label class="font-medium text-gray-700">Proyecto:</label>
                    <p class="text-gray-900">{{ $timeTracking->project->name ?? 'Sin proyecto' }}</p>
                </div>
                
                <div>
                    <label class="font-medium text-gray-700">Categoría:</label>
                    <p class="text-gray-900">{{ $timeTracking->activityCategory->name }}</p>
                </div>
                
                <div>
                    <label class="font-medium text-gray-700">Descripción:</label>
                    <p class="text-gray-900">{{ $timeTracking->description }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-medium text-gray-700">Inicio:</label>
                        <p class="text-gray-900">{{ $timeTracking->start_time->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="font-medium text-gray-700">Fin:</label>
                        <p class="text-gray-900">{{ $timeTracking->end_time->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div>
                    <label class="font-medium text-gray-700">Duración:</label>
                    <p class="text-gray-900">{{ $timeTracking->duration_minutes }} minutos</p>
                </div>
                
                @if($timeTracking->notes)
                <div>
                    <label class="font-medium text-gray-700">Notas:</label>
                    <p class="text-gray-900">{{ $timeTracking->notes }}</p>
                </div>
                @endif
            </div>
            
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('time-trackings.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Volver
                </a>
                <a href="{{ route('time-trackings.edit', $timeTracking) }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection