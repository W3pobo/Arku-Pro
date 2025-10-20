@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-main">{{ $task->title }}</h1>
                <div>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card-custom mb-4">
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-8">
                            @if($task->description)
                                <div class="mb-4">
                                    <h6 class="text-main mb-2">Descripción</h6>
                                    <p class="text-secondary">{{ $task->description }}</p>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Proyecto</small>
                                        <span class="badge-custom bg-accent">{{ $task->project->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Estado</small>
                                        @php $statusBadge = $task->status_badge; @endphp
                                        <span class="badge-custom bg-{{ $statusBadge['color'] }}">{{ $statusBadge['text'] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Prioridad</small>
                                        @php $priorityBadge = $task->priority_badge; @endphp
                                        <span class="badge-custom bg-{{ $priorityBadge['color'] }}">{{ $priorityBadge['text'] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Fecha Límite</small>
                                        <span class="{{ $task->is_overdue ? 'text-danger' : 'text-main' }}">
                                            @if($task->due_date)
                                                {{ $task->due_date->format('d/m/Y') }}
                                                @if($task->is_overdue)
                                                    <span class="badge bg-danger ms-1">Vencida</span>
                                                @endif
                                            @else
                                                <span class="text-secondary">Sin fecha límite</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card-custom border-custom">
                                <div class="card-body-custom">
                                    <h6 class="text-main mb-3">Información de Tiempo</h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Tiempo Estimado</small>
                                        <span class="text-main">
                                            @if($task->estimated_minutes)
                                                {{ floor($task->estimated_minutes / 60) }}h {{ $task->estimated_minutes % 60 }}m
                                            @else
                                                <span class="text-secondary">No estimado</span>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Tiempo Real</small>
                                        <span class="text-main">
                                            @if($task->actual_minutes)
                                                {{ floor($task->actual_minutes / 60) }}h {{ $task->actual_minutes % 60 }}m
                                            @else
                                                <span class="text-secondary">Sin registro</span>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    @if($task->estimated_minutes && $task->actual_minutes)
                                    <div class="mb-3">
                                        <small class="text-secondary d-block">Progreso</small>
                                        <div class="progress-custom mt-1" style="height: 8px;">
                                            <div class="progress-bar-custom bg-{{ $task->progress_percentage >= 100 ? 'success' : 'accent' }}" 
                                                 style="width: {{ min($task->progress_percentage, 100) }}%">
                                            </div>
                                        </div>
                                        <small class="text-secondary">{{ $task->progress_percentage }}%</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Time Tracking -->
            @if($task->timeTrackings->count() > 0)
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Registros de Tiempo</h5>
                </div>
                <div class="card-body-custom">
                    <div class="list-group-custom list-group-flush">
                        @foreach($task->timeTrackings as $tracking)
                        <div class="list-group-item-custom d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 text-main">{{ $tracking->start_time->format('d/m/Y H:i') }}</h6>
                                <small class="text-secondary">
                                    Duración: {{ $tracking->duration_minutes }} minutos • 
                                    Enfoque: {{ $tracking->focus_level }}% • 
                                    Energía: {{ $tracking->energy_level }}%
                                </small>
                            </div>
                            <span class="badge-custom bg-accent">
                                {{ $tracking->activityCategory->name ?? 'Sin categoría' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection