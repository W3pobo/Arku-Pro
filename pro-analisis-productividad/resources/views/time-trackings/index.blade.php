@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Registros de Tiempo</h1>
                <a href="{{ route('time-trackings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($timeTrackings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                    <tr>
                                        <td>{{ $tracking->project->name }}</td>
                                        <td>{{ $tracking->start_time->format('d/m/Y H:i') }}</td>
                                        <td>{{ $tracking->end_time->format('d/m/Y H:i') }}</td>
                                        <td>{{ $tracking->duration_minutes }} minutos</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $tracking->activity_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar 
                                                    @if($tracking->productivity_score >= 80) bg-success
                                                    @elseif($tracking->productivity_score >= 50) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $tracking->productivity_score }}%"
                                                    aria-valuenow="{{ $tracking->productivity_score }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                    {{ $tracking->productivity_score }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('time-trackings.edit', $tracking) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('time-trackings.destroy', $tracking) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('¿Eliminar este registro?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay registros de tiempo aún.</p>
                            <a href="{{ route('time-trackings.create') }}" class="btn btn-primary">
                                Crear primer registro
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection