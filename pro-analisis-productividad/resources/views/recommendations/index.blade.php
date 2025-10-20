@extends('layouts.app')

@section('title', 'Tareas Recomendadas')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-robot me-2"></i>Tareas Recomendadas para Ti
                    </h4>
                    <p class="mb-0 mt-2 small opacity-75">
                        Basado en tu actividad y preferencias de uso
                    </p>
                </div>
                <div class="card-body">
                    <div id="recommendations-container">
                        @if($recommendations->count() > 0)
                            <div class="row">
                                @foreach($recommendations as $task)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card recommendation-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="card-title text-truncate">{{ $task->title }}</h5>
                                                    <span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                
                                                @if($task->description)
                                                    <p class="card-text text-muted small">
                                                        {{ Str::limit($task->description, 100) }}
                                                    </p>
                                                @endif
                                                
                                                <div class="task-meta mb-3">
                                                    @if($task->category)
                                                        <span class="badge bg-info me-1">
                                                            <i class="fas fa-tag me-1"></i>{{ $task->category }}
                                                        </span>
                                                    @endif
                                                    
                                                    @if($task->taskFeature && $task->taskFeature->estimated_duration)
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-clock me-1"></i>{{ $task->taskFeature->estimated_duration }}min
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        Creado: {{ $task->created_at->format('d/m/Y') }}
                                                    </small>
                                                    <button class="btn btn-primary btn-sm view-task" 
                                                            data-task-id="{{ $task->id }}">
                                                        <i class="fas fa-eye me-1"></i>Ver Tarea
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay recomendaciones disponibles</h5>
                                <p class="text-muted">
                                    Completa más tareas para obtener recomendaciones personalizadas.
                                </p>
                                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Crear Nueva Tarea
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registrar interacción al ver recomendaciones
    fetch('{{ route("api.record_interaction") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            task_id: 0, // ID especial para la página de recomendaciones
            interaction_type: 'view'
        })
    });

    // Manejar clic en "Ver Tarea"
    document.querySelectorAll('.view-task').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            
            // Registrar interacción
            fetch('{{ route("api.record_interaction") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    task_id: taskId,
                    interaction_type: 'view'
                })
            }).then(() => {
                // Redirigir a la tarea
                window.location.href = `/tasks/${taskId}`;
            });
        });
    });

    // Actualizar recomendaciones cada 5 minutos
    setInterval(() => {
        fetch('{{ route("api.recommendations") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aquí podrías actualizar el UI dinámicamente
                    console.log('Recomendaciones actualizadas', data.recommendations);
                }
            });
    }, 300000); // 5 minutos
});
</script>
@endpush

@push('styles')
<style>
.recommendation-card {
    border: 2px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
}

.recommendation-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
}

.task-meta .badge {
    font-size: 0.7em;
}

.recommendation-card .card-title {
    color: #2c3e50;
    font-weight: 600;
}

.recommendation-card .card-text {
    color: #6c757d;
    line-height: 1.4;
}
</style>
@endpush