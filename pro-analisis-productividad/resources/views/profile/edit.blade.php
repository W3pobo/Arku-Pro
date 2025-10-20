@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Editar Perfil</h1>
                <p class="page-subtitle">Actualiza tu información personal</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-user-edit me-2"></i>
                        Información Personal
                    </h3>
                </div>
                <div class="chart-body">
                    <form method="POST" action="{{ route('profile.update') }}" class="form-custom">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nombre -->
                        <div class="form-section mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Nombre *
                            </label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name', Auth::user()->name) }}"
                                   class="form-control form-control-dark"
                                   placeholder="Ingresa tu nombre completo">
                            @error('name')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-section mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email *
                            </label>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email', Auth::user()->email) }}"
                                   class="form-control form-control-dark"
                                   placeholder="Ingresa tu dirección de email">
                            @error('email')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="form-actions">
                            <a href="{{ route('profile.show') }}" 
                               class="btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" 
                                    class="btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="chart-card mt-4">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Importante
                    </h3>
                </div>
                <div class="chart-body">
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-shield-alt text-info me-3"></i>
                            <div>
                                <strong>Seguridad:</strong> Tu información está protegida y solo tú puedes acceder a ella.
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-sync text-warning me-3"></i>
                            <div>
                                <strong>Actualizaciones:</strong> Los cambios se reflejarán inmediatamente en tu cuenta.
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-bell text-success me-3"></i>
                            <div>
                                <strong>Notificaciones:</strong> Asegúrate de usar un email válido para recibir notificaciones.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --bg-main: #121826;
    --bg-secondary: #1e293b;
    --bg-dark: #0f172a;
    --accent: #7E57C2;
    --accent-light: #9c7bd4;
    --text-main: #f8fafc;
    --text-light: #e2e8f0;
    --text-muted: #94a3b8;
    --border-color: #334155;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --card-shadow: 0 4px 20px rgba(0,0,0,0.15);
    --card-shadow-hover: 0 8px 30px rgba(126, 87, 194, 0.15);
}

body {
    background-color: var(--bg-main);
    color: var(--text-main);
}

/* === HEADER MEJORADO === */
.page-header {
    text-align: center;
    padding: 2rem 0;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, var(--text-light), var(--accent-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* === TARJETAS DE GRÁFICOS === */
.chart-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.chart-card:hover {
    box-shadow: var(--card-shadow-hover);
}

.chart-header {
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-light);
    margin: 0;
    display: flex;
    align-items: center;
}

.chart-body {
    padding: 2rem;
}

/* === FORMULARIO === */
.form-custom {
    max-width: 100%;
}

.form-section {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.form-control-dark {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-main);
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    width: 100%;
}

.form-control-dark:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--accent);
    color: var(--text-main);
    box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25);
    outline: none;
}

.form-control-dark:hover {
    border-color: var(--border-color);
}

/* === ERRORES === */
.form-error {
    color: var(--warning);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

/* === BOTONES === */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(126, 87, 194, 0.3);
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(126, 87, 194, 0.4);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border: 2px solid var(--border-color);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-secondary:hover {
    background: var(--border-color);
    color: var(--text-main);
    transform: translateY(-2px);
}

/* === INFO LIST === */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.info-item i {
    font-size: 1.25rem;
    margin-top: 0.125rem;
}

.info-item div {
    flex: 1;
}

.info-item strong {
    color: var(--text-light);
    display: block;
    margin-bottom: 0.25rem;
}

.info-item div:not(strong) {
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.4;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .chart-body {
        padding: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .chart-body {
        padding: 1rem;
    }
}
</style>
@endsection