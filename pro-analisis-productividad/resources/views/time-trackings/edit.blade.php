@extends('layouts.app')

@section('title', 'Ver Registro de Tiempo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-secondary rounded-lg shadow-lg p-6 border border-gray-700">
            <h1 class="text-2xl font-bold text-main mb-6">Detalles del Registro de Tiempo</h1>
            
            <div class="space-y-6">
                <div class="detail-item">
                    <label class="font-medium text-secondary">Proyecto:</label>
                    <p class="text-main text-lg">{{ $timeTracking->project->name ?? 'Sin proyecto' }}</p>
                </div>
                
                <div class="detail-item">
                    <label class="font-medium text-secondary">Categoría:</label>
                    <p class="text-main text-lg">{{ $timeTracking->activityCategory->name }}</p>
                </div>
                
                <div class="detail-item">
                    <label class="font-medium text-secondary">Descripción:</label>
                    <p class="text-main text-lg">{{ $timeTracking->description }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="detail-item">
                        <label class="font-medium text-secondary">Inicio:</label>
                        <p class="text-main text-lg">{{ $timeTracking->start_time->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <label class="font-medium text-secondary">Fin:</label>
                        <p class="text-main text-lg">{{ $timeTracking->end_time->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <label class="font-medium text-secondary">Duración:</label>
                    <p class="text-main text-lg">{{ $timeTracking->duration_minutes }} minutos</p>
                </div>
                
                @if($timeTracking->notes)
                <div class="detail-item">
                    <label class="font-medium text-secondary">Notas:</label>
                    <p class="text-main text-lg">{{ $timeTracking->notes }}</p>
                </div>
                @endif
            </div>
            
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('time-trackings.index') }}" 
                   class="btn-secondary px-6 py-3 rounded-lg transition duration-200 transform hover:-translate-y-1">
                    Volver
                </a>
                <a href="{{ route('time-trackings.edit', $timeTracking) }}" 
                   class="btn-primary px-6 py-3 rounded-lg transition duration-200 transform hover:-translate-y-1">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --bg-main: #121826;
    --bg-secondary: #2A3241;
    --accent: #7E57C2;
    --text-main: #F0F2F5;
    --text-secondary: #A9B4C7;
    --hover-accent: #6a4da2;
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

.detail-item {
    padding: 1rem;
    border-radius: 8px;
    background-color: rgba(255,255,255,0.05);
    transition: background-color 0.3s ease;
}

.detail-item:hover {
    background-color: rgba(126, 87, 194, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--hover-accent));
    color: white;
    border: none;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary:hover {
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    background: linear-gradient(135deg, var(--hover-accent), var(--accent));
    color: white;
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: transparent;
    border: 2px solid var(--text-secondary);
    color: var(--text-secondary);
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-secondary:hover {
    background-color: var(--accent);
    border-color: var(--accent);
    color: white;
    transform: translateY(-2px);
}
</style>
@endsection