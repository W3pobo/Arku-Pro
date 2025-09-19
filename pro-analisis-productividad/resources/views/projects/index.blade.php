@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mis Proyectos</h1>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Proyecto
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($projects->count() > 0)
                <div class="row">
                    @foreach($projects as $project)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $project->name }}</h5>
                                <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                                <p class="card-text">
                                    <strong>Estado:</strong> 
                                    <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">
                                        {{ $project->status }}
                                    </span>
                                </p>
                                <p class="card-text">
                                    <strong>Horas registradas:</strong> 
                                    {{ number_format($project->timeTrackings->sum('duration_minutes') / 60, 1) }}h
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                    Ver Detalles
                                </a>
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
                                    Editar
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('¿Eliminar este proyecto?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes proyectos aún</h4>
                    <p class="text-muted">Comienza creando tu primer proyecto para organizar tu trabajo</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        Crear Primer Proyecto
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection