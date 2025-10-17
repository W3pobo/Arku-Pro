@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-main">Registros de Tiempo</h1>
                <a href="{{ route('time-trackings.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
            </div>

            @if(session('success'))
                <div class="alert-custom alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="card-custom">
                <div class="card-body">
                    @if($timeTrackings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Duración</th>
                                        <th>Tipo</th>
                                        <th>Productividad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($timeTrackings as $tracking)
                                    <tr class="table-row-custom">
                                        <td class="text-main">{{ $tracking->project->name ?? 'Sin proyecto' }}</td>
                                        <td class="text-secondary">{{ $tracking->start_time->format('d/m/Y H:i') }}</td>
                                        <td class="text-secondary">{{ $tracking->end_time->format('d/m/Y H:i') }}</td>
                                        <td class="text-accent">{{ $tracking->duration_minutes }} minutos</td>
                                        <td>
                                            <span class="badge-custom">
                                                {{ $tracking->activity_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress-custom">
                                                <div class="progress-bar-custom 
                                                    @if($tracking->productivity_score >= 80) bg-success
                                                    @elseif($tracking->productivity_score >= 50) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                    style="width: {{ $tracking->productivity_score }}%">
                                                    <span class="progress-text">{{ $tracking->productivity_score }}%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group-custom">
                                                <a href="{{ route('time-trackings.edit', $tracking) }}" 
                                                   class="btn-action btn-edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('time-trackings.destroy', $tracking) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete" 
                                                            onclick="return confirm('¿Eliminar este registro?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-clock fa-4x text-accent mb-3"></i>
                                <h3 class="text-main mb-3">No hay registros de tiempo aún</h3>
                                <p class="text-secondary mb-4">Comienza a trackear tu tiempo para ver tus estadísticas</p>
                                <a href="{{ route('time-trackings.create') }}" class="btn-primary">
                                    Crear primer registro
                                </a>
                            </div>
                        </div>
                    @endif
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
    gap: 8px;
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
}

.alert-custom {
    background-color: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.3);
    border-radius: 8px;
    padding: 12px 16px;
    color: var(--text-main);
}

.table-dark {
    background-color: transparent;
    color: var(--text-main);
}

.table-row-custom {
    transition: background-color 0.3s ease;
}

.table-row-custom:hover {
    background-color: rgba(126, 87, 194, 0.1);
}

.badge-custom {
    background: rgba(126, 87, 194, 0.2);
    color: var(--accent);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    border: 1px solid rgba(126, 87, 194, 0.3);
}

.progress-custom {
    height: 24px;
    background-color: rgba(255,255,255,0.1);
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}

.progress-bar-custom {
    height: 100%;
    border-radius: 12px;
    position: relative;
    transition: width 0.5s ease;
}

.progress-text {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.8em;
    font-weight: 600;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
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

.btn-edit {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.btn-edit:hover {
    background: #3b82f6;
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