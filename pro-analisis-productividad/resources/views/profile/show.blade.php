@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="background-color: #2A3241;">
                <div class="card-header border-0" style="background-color: #121826;">
                    <h4 class="mb-0 text-light">Mi Perfil</h4>
                </div>
                <div class="card-body text-light">
                    <div class="profile-info">
                        <div class="row mb-4 py-2" style="border-bottom: 1px solid #3a4251;">
                            <div class="col-sm-3 fw-semibold" style="color: #A9B4C7;">Nombre:</div>
                            <div class="col-sm-9 fw-medium" style="color: #F0F2F5;">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="row mb-4 py-2" style="border-bottom: 1px solid #3a4251;">
                            <div class="col-sm-3 fw-semibold" style="color: #A9B4C7;">Email:</div>
                            <div class="col-sm-9 fw-medium" style="color: #F0F2F5;">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="row mb-4 py-2" style="border-bottom: 1px solid #3a4251;">
                            <div class="col-sm-3 fw-semibold" style="color: #A9B4C7;">Miembro desde:</div>
                            <div class="col-sm-9 fw-medium" style="color: #F0F2F5;">
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #7E57C2;">
                                    {{ Auth::user()->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 d-flex gap-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary border-0 fw-semibold px-4 py-2"
                           style="background: linear-gradient(135deg, #7E57C2, #9575CD); transition: all 0.3s ease;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-edit me-2"></i>Editar Perfil
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary border-0 fw-semibold px-4 py-2"
                           style="background-color: #2A3241; color: #A9B4C7; border: 1px solid #3a4251 !important; transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='#3a4251'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.2)'"
                           onmouseout="this.style.backgroundColor='#2A3241'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #121826 !important;
    }
    
    .profile-info .row {
        transition: background-color 0.3s ease;
    }
    
    .profile-info .row:hover {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
    }
</style>
@endsection