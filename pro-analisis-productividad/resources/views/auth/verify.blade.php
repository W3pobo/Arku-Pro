@extends('layouts.app')

@section('title', 'Verificar Email')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="background-color: #2A3241;">
                <div class="card-header border-0 text-center py-4" style="background-color: #121826;">
                    <i class="fas fa-envelope-circle-check fa-3x mb-3" style="color: #7E57C2;"></i>
                    <h3 class="mb-0" style="color: #F0F2F5;">Verifica tu dirección de email</h3>
                </div>

                <div class="card-body text-center py-5">
                    @if (session('resent'))
                        <div class="alert alert-success border-0 mb-4 d-flex align-items-center justify-content-center" role="alert" 
                             style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border-radius: 12px;">
                            <i class="fas fa-check-circle me-3 fs-5"></i>
                            <div>{{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de email.') }}</div>
                        </div>
                    @endif

                    <p class="mb-4" style="color: #A9B4C7; font-size: 1.1rem;">
                        <i class="fas fa-info-circle me-2" style="color: #7E57C2;"></i>
                        {{ __('Antes de continuar, por favor revisa tu email para obtener el enlace de verificación.') }}
                    </p>
                    
                    <p class="mb-4" style="color: #A9B4C7;">
                        {{ __('Si no recibiste el email') }},
                    </p>

                    <form class="d-inline mb-4" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" 
                                class="btn btn-primary border-0 px-4 py-2 fw-semibold"
                                style="background: linear-gradient(135deg, #7E57C2, #9575CD); transition: all 0.3s ease;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-paper-plane me-2"></i>
                            {{ __('haz clic aquí para solicitar otro') }}
                        </button>
                    </form>

                    <div class="mt-5 pt-4" style="border-top: 1px solid #3a4251;">
                        <p class="small mb-3" style="color: #A9B4C7;">
                            <i class="fas fa-question-circle me-2"></i>
                            ¿Necesitas ayuda?
                        </p>
                        <a href="mailto:soporte@arkupro.com" 
                           class="btn btn-outline-secondary border-0 px-4 py-2"
                           style="color: #A9B4C7; background-color: #121826; transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='#7E57C2'; this.style.color='#F0F2F5'"
                           onmouseout="this.style.backgroundColor='#121826'; this.style.color='#A9B4C7'">
                            <i class="fas fa-headset me-2"></i>
                            Contactar Soporte
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
</style>
@endsection
