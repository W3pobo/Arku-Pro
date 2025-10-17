<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ArkuPro</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-primary: #121826;
            --bg-secondary: #2A3241;
            --accent: #7E57C2;
            --accent-hover: #9575CD;
            --text-primary: #F0F2F5;
            --text-secondary: #A9B4C7;
            --success: #22c55e;
            --warning: #eab308;
            --danger: #ef4444;
            --border-color: #3a4251;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, #1a2233 100%);
            color: var(--text-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .auth-container {
            background: var(--bg-secondary);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }
        
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--accent-hover));
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .auth-logo {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .auth-title {
            font-size: 1.8rem;
            color: var(--text-primary);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .auth-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }
        
        .auth-form {
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        
        .form-control:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(126, 87, 194, 0.2);
            transform: translateY(-2px);
        }
        
        .form-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 6px;
        }
        
        .btn {
            padding: 15px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: var(--text-primary);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(126, 87, 194, 0.4);
        }
        
        .btn-google {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }
        
        .btn-google:hover {
            background: var(--border-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .auth-divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: var(--text-secondary);
        }
        
        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }
        
        .auth-divider span {
            padding: 0 15px;
            font-size: 0.9rem;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 25px;
        }
        
        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .auth-footer a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            border: none;
            backdrop-filter: blur(10px);
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle .form-control {
            padding-right: 50px;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 6px;
        }
        
        .toggle-password:hover {
            color: var(--accent);
            background: rgba(126, 87, 194, 0.1);
        }
        
        .password-strength {
            margin-top: 8px;
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: var(--border-color);
            overflow: hidden;
            margin-bottom: 5px;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="auth-container">
            <div class="auth-header">
                <div class="auth-logo">ArkuPro</div>
                <h1 class="auth-title">Crear Cuenta</h1>
                <p class="auth-subtitle">Comienza tu journey de productividad</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="alert alert-danger" v-if="errorMessage">
                @{{ errorMessage }}
            </div>

            <a href="{{ route('google.login') }}" class="btn btn-google">
                <i class="fab fa-google"></i>
                Registrarse con Google
            </a>

            <div class="auth-divider">
                <span>O regístrate con email</span>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i>Nombre completo
                    </label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required 
                           autofocus autocomplete="name" placeholder="Tu nombre completo">
                    @error('name')
                        <span class="form-text" style="color: var(--danger);">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>Correo electrónico
                    </label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required 
                           autocomplete="email" placeholder="tu@email.com">
                    @error('email')
                        <span class="form-text" style="color: var(--danger);">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>Contraseña
                    </label>
                    <div class="password-toggle">
                        <input :type="showPassword ? 'text' : 'password'" class="form-control" id="password" name="password" 
                               required autocomplete="new-password" placeholder="••••••••" @input="checkPasswordStrength">
                        <button type="button" class="toggle-password" @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    <div class="password-strength" v-if="password.length > 0">
                        <div class="strength-bar">
                            <div class="strength-fill" :style="strengthStyle"></div>
                        </div>
                        <div class="strength-text">
                            @{{ strengthText }}
                        </div>
                    </div>
                    @error('password')
                        <span class="form-text" style="color: var(--danger);">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-lock"></i>Confirmar contraseña
                    </label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                           required autocomplete="new-password" placeholder="••••••••">
                    @error('password_confirmation')
                        <span class="form-text" style="color: var(--danger);">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Crear Cuenta
                </button>
            </form>
            
            <div class="auth-footer">
                <p>
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const { createApp, ref, computed } = Vue;
        
        createApp({
            setup() {
                const showPassword = ref(false);
                const errorMessage = ref('');
                const successMessage = ref('');
                const password = ref('');
                
                const checkPasswordStrength = (event) => {
                    password.value = event.target.value;
                };
                
                const passwordStrength = computed(() => {
                    const pass = password.value;
                    let strength = 0;
                    
                    if (pass.length >= 8) strength += 25;
                    if (pass.match(/[a-z]/) && pass.match(/[A-Z]/)) strength += 25;
                    if (pass.match(/\d/)) strength += 25;
                    if (pass.match(/[^a-zA-Z\d]/)) strength += 25;
                    
                    return Math.min(strength, 100);
                });
                
                const strengthStyle = computed(() => {
                    let color = '#ef4444'; // red
                    if (passwordStrength.value >= 75) color = '#22c55e'; // green
                    else if (passwordStrength.value >= 50) color = '#eab308'; // yellow
                    else if (passwordStrength.value >= 25) color = '#f97316'; // orange
                    
                    return {
                        width: `${passwordStrength.value}%`,
                        backgroundColor: color
                    };
                });
                
                const strengthText = computed(() => {
                    if (password.value.length === 0) return '';
                    if (passwordStrength.value >= 75) return 'Fuerte';
                    if (passwordStrength.value >= 50) return 'Media';
                    if (passwordStrength.value >= 25) return 'Débil';
                    return 'Muy débil';
                });
                
                @if ($errors->any())
                    errorMessage.value = 'Por favor corrige los errores en el formulario.';
                @endif
                
                @if (session('status'))
                    successMessage.value = '{{ session('status') }}';
                @endif
                
                return {
                    showPassword,
                    errorMessage,
                    successMessage,
                    password,
                    checkPasswordStrength,
                    strengthStyle,
                    strengthText
                };
            }
        }).mount('#app');
    </script>
</body>
</html>