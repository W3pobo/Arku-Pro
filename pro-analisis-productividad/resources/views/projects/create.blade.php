@extends('layouts.app')

@section('title', 'Crear Nuevo Proyecto')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>Crear Nuevo Proyecto</h1></div>

                <div class="card-body">
                    {{-- ========================================================= --}}
                    {{-- ESTE ES EL BLOQUE QUE NECESITAS AÑADIR/ASEGURAR QUE EXISTA --}}
                    {{-- ========================================================= --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>¡Ups! Hubo algunos problemas con tu entrada.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- ========================================================= --}}

                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Proyecto</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>Pausado</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Proyecto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection