@extends('layouts.app')

@section('title', 'Detalles del Proyecto')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $project->name }}</h1>
        <div>
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary">Editar</a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este proyecto?')">
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Detalles</div>
                <div class="card-body">
                    <p><strong>Descripción:</strong></p>
                    <p>{{ $project->description ?: 'No hay descripción.' }}</p>
                    <hr>
                    <p><strong>Estado:</strong> <span class="badge bg-primary">{{ $project->status }}</span></p>
                    <p><strong>Horas Totales:</strong> {{ number_format($totalHours, 1) }}h</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registros de Tiempo</div>
                <div class="card-body">
                    @forelse($project->timeTrackings as $tracking)
                        <div class="mb-2">
                            <strong>{{ $tracking->activity_type }}:</strong>
                            <span>{{ $tracking->duration_minutes }} minutos</span>
                            <small class="text-muted">({{ $tracking->start_time->format('d/m/Y') }})</small>
                        </div>
                    @empty
                        <p>No hay registros de tiempo para este proyecto.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection