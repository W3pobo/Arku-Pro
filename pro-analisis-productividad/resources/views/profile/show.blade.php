@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Mi Perfil</h1>
                <p class="page-subtitle">Información de tu cuenta y estadísticas</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-user-circle me-2"></i>
                        Información del Perfil
                    </h3>
                    <div class="table-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Perfil
                        </a>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="profile-details">
                        <!-- Nombre -->
                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-user detail-icon"></i>
                                <div class="detail-info">
                                    <label class="detail-label">Nombre</label>
                                    <div class="detail-value">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-envelope detail-icon"></i>
                                <div class="detail-info">
                                    <label class="detail-label">Email</label>
                                    <div class="detail-value">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha de Registro -->
                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-calendar-plus detail-icon"></i>
                                <div class="detail-info">
                                    <label class="detail-label">Miembro desde</label>
                                    <div class="detail-value">
                                        <span class="membership-badge">
                                            <i class="fas fa-star me-2"></i>
                                            {{ Auth::user()->created_at->format('d/m/Y') }}
                                        </span>
                                        <small class="membership-days">
                                            ({{ Auth::user()->created_at->diffForHumans() }})
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ID de Usuario -->
                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-id-card detail-icon"></i>
                                <div class="detail-info">
                                    <label class="detail-label">ID de Usuario</label>
                                    <div class="detail-value">
                                        <code class="user-id">{{ Auth::id() }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">--</div>
                            <div class="stat-label">Horas Registradas</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">--</div>
                            <div class="stat-label">Proyectos Activos</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">--</div>
                            <div class="stat-label">Tareas Completadas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="chart-card mt-4">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-rocket me-2"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="chart-body">
                    <div class="quick-actions">
                        <a href="{{ route('dashboard') }}" class="quick-action">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('time-trackings.create') }}" class="quick-action">
                            <i class="fas fa-plus-circle"></i>
                            <span>Nuevo Registro</span>
                        </a>
                        <a href="{{ route('projects.index') }}" class="quick-action">
                            <i class="fas fa-folder"></i>
                            <span>Mis Proyectos</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="quick-action">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reportes</span>
                        </a>
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

.table-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
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

.chart-body {
    padding: 2rem;
}

/* === DETALLES DEL PERFIL === */
.profile-details {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.detail-section {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.detail-section:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.detail-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.detail-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.detail-info {
    flex: 1;
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: block;
}

.detail-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-light);
}

.membership-badge {
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.membership-days {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-left: 0.75rem;
}

.user-id {
    background: rgba(255, 255, 255, 0.1);
    color: var(--accent-light);
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    border: 1px solid var(--border-color);
}

/* === ESTADÍSTICAS === */
.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: var(--card-shadow-hover);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* === ACCIONES RÁPIDAS === */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.quick-action {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    color: var(--text-light);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    text-align: center;
}

.quick-action:hover {
    background: rgba(126, 87, 194, 0.1);
    border-color: var(--accent);
    color: var(--text-light);
    transform: translateY(-2px);
    box-shadow: var(--card-shadow-hover);
}

.quick-action i {
    font-size: 2rem;
    color: var(--accent);
}

.quick-action span {
    font-weight: 600;
    font-size: 0.9rem;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .chart-body {
        padding: 1rem;
    }
    
    .detail-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
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
    
    .detail-section {
        padding: 1rem;
    }
}
</style>
@endsection