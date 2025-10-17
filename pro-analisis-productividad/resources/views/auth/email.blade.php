@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="background-color: #2A3241;">
                <div class="card-header border-0 text-center py-4" style="background-color: #121826;">
                    <i class="fas fa-envelope fa-3x mb-3" style="color: #7E57C2;"></i>
                    <h3 class="mb-0" style="color: #F0F2F5;">{{ __('Recuperar Contraseña') }}</h3>
                </div>

                <div class="card-body py-4">
                    @if (session('status'))
                        <div class="alert alert-success border-0 mb-4 d-flex align-items-center" role="alert" 
                             style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border-radius: 12px;">
                            <i class="fas fa-check-circle me-3 fs-5"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

                    <p class="text-center mb-4" style="color: #A9B4C7;">
                        <i class="fas fa-info-circle me-2" style="color: #7E57C2;"></i>
                        {{ __('Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.') }}
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end" style="color: #A9B4C7;">
                                <i class="fas fa-envelope me-2" style="color: #7E57C2;"></i>
                                {{ __('Email Address') }}
                            </label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       style="background-color: #121826; border: 1px solid #3a4251; color: #F0F2F5;"
                                       placeholder="tu@email.com">

                                @error('email')
                                    <span class="invalid-feedback d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" 
                                        class="btn btn-primary border-0 px-4 py-2 fw-semibold w-100"
                                        style="background: linear-gradient(135deg, #7E57C2, #9575CD); transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    {{ __('Enviar Enlace de Recuperación') }}
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-4 text-center">
                                <a href="{{ route('login') }}" 
                                   class="btn btn-outline-secondary border-0 px-4 py-2"
                                   style="color: #A9B4C7; background-color: transparent; transition: all 0.3s ease;"
                                   onmouseover="this.style.backgroundColor='#7E57C2'; this.style.color='#F0F2F5'"
                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#A9B4C7'">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Volver al Login
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #121826 !important;
    }
    
    .form-control:focus {
        background-color: #121826;
        border-color: #7E57C2;
        color: #F0F2F5;
        box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25);
    }
    
    .form-control::placeholder {
        color: #6c757d;
    }
</style>
@endsection
