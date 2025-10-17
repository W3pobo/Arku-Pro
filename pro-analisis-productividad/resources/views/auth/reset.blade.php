@extends('layouts.app')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="background-color: #2A3241;">
                <div class="card-header border-0 text-center py-4" style="background-color: #121826;">
                    <i class="fas fa-key fa-3x mb-3" style="color: #7E57C2;"></i>
                    <h3 class="mb-0" style="color: #F0F2F5;">{{ __('Restablecer Contraseña') }}</h3>
                </div>

                <div class="card-body py-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end" style="color: #A9B4C7;">
                                <i class="fas fa-envelope me-2" style="color: #7E57C2;"></i>
                                {{ __('Email Address') }}
                            </label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ $email ?? old('email') }}" required 
                                       autocomplete="email" autofocus
                                       style="background-color: #121826; border: 1px solid #3a4251; color: #F0F2F5;">

                                @error('email')
                                    <span class="invalid-feedback d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end" style="color: #A9B4C7;">
                                <i class="fas fa-lock me-2" style="color: #7E57C2;"></i>
                                {{ __('Password') }}
                            </label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       style="background-color: #121826; border: 1px solid #3a4251; color: #F0F2F5;">

                                @error('password')
                                    <span class="invalid-feedback d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end" style="color: #A9B4C7;">
                                <i class="fas fa-lock me-2" style="color: #7E57C2;"></i>
                                {{ __('Confirm Password') }}
                            </label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       style="background-color: #121826; border: 1px solid #3a4251; color: #F0F2F5;">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" 
                                        class="btn btn-primary border-0 px-4 py-2 fw-semibold w-100"
                                        style="background: linear-gradient(135deg, #7E57C2, #9575CD); transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-key me-2"></i>
                                    {{ __('Restablecer Contraseña') }}
                                </button>
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