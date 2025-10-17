@extends('layouts.app')

@section('title', 'Detalles del Proyecto')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-main">{{ $project->name }}</h1>
        <div class="btn-group-custom">
            <a href="{{ route('projects.edit', $project) }}" class="btn-edit">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar este proyecto?')">
                    <i class="fas fa-trash mr-2"></i>Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Detalles del Proyecto</h5>
                </div>
                <div class="card-body-custom">
                    <div class="detail-item">
                        <label class="text-secondary">Descripción:</label>
                        <p class="text-main">{{ $project->description ?: 'No hay descripción.' }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <label class="text-secondary">Estado:</label>
                        <span class="badge-custom bg-primary">{{ $project->status }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <label class="text-secondary">Horas Totales:</label>
                        <p class="text-accent font-weight-bold">{{ number_format($totalHours, 1) }}h</p>
                    </div>

                    @if($project->start_date)
                    <div class="detail-item">
                        <label class="text-secondary">Fecha de Inicio:</label>
                        <p class="text-main">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</p>
                    </div>
                    @endif

                    @if($project->deadline)
                    <div class="detail-item">
                        <label class="text-secondary">Fecha Límite:</label>
                        <p class="text-main">{{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}</p>
                    </div>
                    @endif

                    @if($project->color)
                    <div class="detail-item">
                        <label class="text-secondary">Color:</label>
                        <div class="color-display" style="background-color: {{ $project->color }}"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom mb-0">Registros de Tiempo</h5>
                </div>
                <div class="card-body-custom">
                    @forelse($project->timeTrackings as $tracking)
                        <div class="time-tracking-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="text-main">{{ $tracking->activity_type }}:</strong>
                                    <span class="text-secondary">{{ $tracking->duration_minutes }} minutos</span>
                                    <small class="text-muted">({{ $tracking->start_time->format('d/m/Y H:i') }})</small>
                                </div>
                                @if($tracking->description)
                                <span class="badge-custom bg-secondary" title="{{ $tracking->description }}">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                @endif
                            </div>
                            @if($tracking->description)
                            <p class="text-secondary small mt-1 mb-0">{{ Str::limit($tracking->description, 100) }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-accent mb-3"></i>
                            <p class="text-secondary">No hay registros de tiempo para este proyecto.</p>
                            <a href="{{ route('time-trackings.create') }}" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i>Agregar Registro
                            </a>
                        </div>
                    @endforelse
                </div>
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
    --border-color: #3a4458;
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

.text-main { color: var(--text-main); }
.text-secondary { color: var(--text-secondary); }
.text-accent { color: var(--accent); }

.card-custom {
    background-color: var(--bg-secondary);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
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

.card-header-custom {
    background-color: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
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

.detail-item {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.detail-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.badge-custom {
    background-color: rgba(126, 87, 194, 0.2);
    color: var(--accent);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    border: 1px solid rgba(126, 87, 194, 0.3);
}

.bg-primary { background: linear-gradient(135deg, var(--accent), var(--hover-accent)) !important; }
.bg-secondary { background: rgba(255,255,255,0.1) !important; color: var(--text-secondary) !important; }

.color-display {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 2px solid rgba(255,255,255,0.2);
}

.time-tracking-item {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    transition: background-color 0.3s ease;
}

.time-tracking-item:hover {
    background-color: rgba(126, 87, 194, 0.05);
}

.time-tracking-item:last-child {
    border-bottom: none;
}

.btn-group-custom {
    display: flex;
    gap: 12px;
}

.btn-edit {
    background: linear-gradient(135deg, var(--accent), var(--hover-accent));
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    cursor: pointer;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--hover-accent));
    color: white;
    border: none;
    padding: 10px 20px;
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
</style>
@endsection