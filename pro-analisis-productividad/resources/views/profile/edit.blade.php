@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" style="background-color: #2A3241;">
                <div class="card-header border-0" style="background-color: #121826;">
                    <h4 class="mb-0 text-light">Editar Perfil</h4>
                </div>
                <div class="card-body text-light">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold" style="color: #F0F2F5;">Nombre</label>
                            <input type="text" class="form-control border-0 py-2" id="name" name="name" 
                                   value="{{ old('name', Auth::user()->name) }}" required
                                   style="background-color: #121826; color: #F0F2F5; transition: all 0.3s ease;"
                                   onfocus="this.style.backgroundColor='#1a2233'; this.style.boxShadow='0 0 0 0.2rem rgba(126, 87, 194, 0.25)'"
                                   onblur="this.style.backgroundColor='#121826'; this.style.boxShadow='none'">
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #F0F2F5;">Email</label>
                            <input type="email" class="form-control border-0 py-2" id="email" name="email" 
                                   value="{{ old('email', Auth::user()->email) }}" required
                                   style="background-color: #121826; color: #F0F2F5; transition: all 0.3s ease;"
                                   onfocus="this.style.backgroundColor='#1a2233'; this.style.boxShadow='0 0 0 0.2rem rgba(126, 87, 194, 0.25)'"
                                   onblur="this.style.backgroundColor='#121826'; this.style.boxShadow='none'">
                        </div>
                        
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary border-0 fw-semibold px-4 py-2"
                                    style="background: linear-gradient(135deg, #7E57C2, #9575CD); transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(126, 87, 194, 0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary border-0 fw-semibold px-4 py-2"
                               style="background-color: #2A3241; color: #A9B4C7; transition: all 0.3s ease;"
                               onmouseover="this.style.backgroundColor='#3a4251'; this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.backgroundColor='#2A3241'; this.style.transform='translateY(0)'">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #7E57C2 !important;
        box-shadow: 0 0 0 0.2rem rgba(126, 87, 194, 0.25) !important;
    }
    
    body {
        background-color: #121826 !important;
    }
</style>
@endsection