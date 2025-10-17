@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-main">Mis Proyectos</h1>
                <a href="{{ route('projects.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Nuevo Proyecto
                </a>
            </div>

            @if(session('success'))
                <div class="alert-custom alert-success mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($projects->count() > 0)
                <div class="row">
                    @foreach($projects as $project)
                    <div class="col-md-4 mb-4">
                        <div class="card-custom h-100">
                            <div class="card-body-custom">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title-custom">{{ $project->name }}</h5>
                                    <span class="badge-custom bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">
                                        {{ $project->status }}
                                    </span>
                                </div>
                                <p class="card-text-custom">{{ Str::limit($project->description, 100) }}</p>
                                <div class="project-stats mt-3">
                                    <p class="text-secondary mb-1">
                                        <i class="fas fa-clock mr-2"></i>
                                        <strong>Horas registradas:</strong> 
                                        {{ number_format($project->timeTrackings->sum('duration_minutes') / 60, 1) }}h
                                    </p>
                                    @if($project->color)
                                    <div class="color-preview mt-2">
                                        <span class="text-secondary text-sm">Color:</span>
                                        <div class="color-dot" style="background-color: {{ $project->color }}"></div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer-custom">
                                <div class="btn-group-custom">
                                    <a href="{{ route('projects.show', $project) }}" class="btn-action btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" 
                                                onclick="return confirm('¿Eliminar este proyecto?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-folder-open fa-4x text-accent mb-3"></i>
                        <h3 class="text-main mb-3">No tienes proyectos aún</h3>
                        <p class="text-secondary mb-4">Comienza creando tu primer proyecto para organizar tu trabajo</p>
                        <a href="{{ route('projects.create') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Crear Primer Proyecto
                        </a>
                    </div>
                </div>
            @endif
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
    --border-color: #3a4458;
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

.text-main { color: var(--text-main); }
.text-secondary { color: var(--text-secondary); }
.text-accent { color: var(--accent); }

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--hover-accent));
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    color: white;
}

.card-custom {
    background-color: var(--bg-secondary);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), transparent);
}

.card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(126, 87, 194, 0.15);
}

.card-body-custom {
    padding: 1.5rem;
    color: var(--text-main);
}

.card-title-custom {
    color: var(--text-main);
    font-weight: 600;
    margin-bottom: 0;
}

.card-text-custom {
    color: var(--text-secondary);
    margin-bottom: 0;
}

.card-footer-custom {
    background-color: rgba(255,255,255,0.05);
    border-top: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
}

.alert-custom {
    background-color: rgba(46, 204, 113, 0.1);
    border: 1px solid rgba(46, 204, 113, 0.3);
    border-radius: 8px;
    padding: 12px 16px;
    color: var(--text-main);
}

.badge-custom {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,0.1);
}

.bg-success { background: linear-gradient(135deg, #2ecc71, #27ae60) !important; }
.bg-primary { background: linear-gradient(135deg, var(--accent), var(--hover-accent)) !important; }
.bg-warning { background: linear-gradient(135deg, #f39c12, #e67e22) !important; }

.color-preview {
    display: flex;
    align-items: center;
    gap: 8px;
}

.color-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.2);
}

.btn-group-custom {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-info {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.btn-info:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.btn-edit {
    background: rgba(126, 87, 194, 0.2);
    color: var(--accent);
    border: 1px solid rgba(126, 87, 194, 0.3);
}

.btn-edit:hover {
    background: var(--accent);
    color: white;
    transform: translateY(-2px);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}

.empty-state {
    padding: 2rem;
}

.empty-state i {
    opacity: 0.8;
}
</style>
@endsection