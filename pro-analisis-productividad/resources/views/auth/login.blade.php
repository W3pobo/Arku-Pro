<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #34495e;
            --gray: #95a5a6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .auth-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .auth-logo {
            font-weight: 700;
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .auth-logo span {
            color: var(--secondary);
        }
        
        .auth-title {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .auth-subtitle {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .auth-form {
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }
        
        .form-text {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 5px;
        }
        
        .btn {
            padding: 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-google {
            background-color: white;
            color: #757575;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .btn-google:hover {
            background-color: #f5f5f5;
        }
        
        .btn-google img {
            width: 20px;
            margin-right: 10px;
        }
        
        .auth-divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        
        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .auth-divider span {
            padding: 0 10px;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success);
            border: 1px solid rgba(46, 204, 113, 0.2);
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle .form-control {
            padding-right: 40px;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray);
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="auth-container">
            <div class="auth-header">
                <div class="auth-logo">Arku<span>Pro</span></div>
                <h1 class="auth-title" v-if="isLoginMode">Iniciar Sesión</h1>
                <h1 class="auth-title" v-else>Crear Cuenta</h1>
                <p class="auth-subtitle" v-if="isLoginMode">Ingresa a tu dashboard de productividad</p>
                <p class="auth-subtitle" v-else>Checando que tan productivo eres</p>
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

            <div class="alert alert-success" v-if="successMessage">
                @{{ successMessage }}
            </div>

            <a href="{{ route('google.login') }}" class="btn btn-google">
                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=" alt="Google">
                @{{ isLoginMode ? 'Iniciar sesión con Google' : 'Registrarse con Google' }}
            </a>

            <div class="auth-divider">
                <span>O</span>
            </div>

            <form class="auth-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group" v-if="!isLoginMode">
                    <label for="name">Nombre completo</label>
                    <input type="text" class="form-control" id="name" v-model="form.name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-toggle">
                        <input :type="showPassword ? 'text' : 'password'" class="form-control" id="password" name="password" required>
                        <button type="button" class="toggle-password" @click="showPassword = !showPassword">
                            <span v-if="showPassword">👁️</span>
                            <span v-else>👁️</span>
                        </button>
                    </div>
                    <p class="form-text" v-if="!isLoginMode">La contraseña debe tener al menos 8 caracteres</p>
                </div>
                
                <div class="form-group" v-if="!isLoginMode">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    @{{ isLoginMode ? 'Iniciar Sesión' : 'Crear Cuenta' }}
                </button>
            </form>
            
            <div class="auth-footer">
                <p v-if="isLoginMode">
                    ¿No tienes una cuenta? <a href="#" @click="toggleMode">Regístrate</a>
                </p>
                <p v-else>
                    ¿Ya tienes una cuenta? <a href="#" @click="toggleMode">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const { createApp, ref } = Vue;
        
        createApp({
            setup() {
                const isLoginMode = ref(true);
                const showPassword = ref(false);
                const errorMessage = ref('');
                const successMessage = ref('');
                
                const form = ref({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                });
                
                const toggleMode = () => {
                    isLoginMode.value = !isLoginMode.value;
                    errorMessage.value = '';
                    successMessage.value = '';
                };
                
                const validateForm = () => {
                    if (!isLoginMode.value) {
                        // Validación de registro
                        if (form.value.name.length < 3) {
                            errorMessage.value = 'El nombre debe tener al menos 3 caracteres';
                            return false;
                        }
                        
                        if (form.value.password.length < 8) {
                            errorMessage.value = 'La contraseña debe tener al menos 8 caracteres';
                            return false;
                        }
                        
                        if (form.value.password !== form.value.password_confirmation) {
                            errorMessage.value = 'Las contraseñas no coinciden';
                            return false;
                        }
                    }
                    
                    // Validación de email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(form.value.email)) {
                        errorMessage.value = 'Por favor ingresa un correo electrónico válido';
                        return false;
                    }
                    
                    return true;
                };
                
                const submitForm = () => {
                    errorMessage.value = '';
                    
                    if (!validateForm()) {
                        return;
                    }
                    
                    // En una aplicación real, aquí harías una petición a tu API de Laravel
                    if (isLoginMode.value) {
                        // Redirigir al endpoint de login de Laravel
                        window.location.href = '/login';
                    } else {
                        // Redirigir al endpoint de registro de Laravel
                        window.location.href = '/register';
                    }
                };
                
                return {
                    isLoginMode,
                    showPassword,
                    form,
                    errorMessage,
                    successMessage,
                    toggleMode,
                    submitForm
                };
            }
        }).mount('#app');
    </script>

    {{-- Tu script de Vue.js existente va aquí arriba --}}
    
    {{-- AGREGA ESTE NUEVO SCRIPT AQUÍ ABAJO --}}
    <script>
        // Espera a que todo el contenido de la página se cargue
        document.addEventListener('DOMContentLoaded', () => {
            // Busca el contenedor de errores de Laravel
            const errorContainer = document.querySelector('.alert-danger ul');
            if (!errorContainer) return;

            // Busca específicamente el mensaje de error de "throttle"
            const throttleErrorListItem = Array.from(errorContainer.querySelectorAll('li'))
                .find(li => li.textContent.includes('Demasiados intentos de acceso'));

            if (throttleErrorListItem) {
                const originalText = throttleErrorListItem.textContent;
                
                // Usa una expresión regular para encontrar el número de segundos
                const matches = originalText.match(/\d+/);

                if (matches) {
                    let seconds = parseInt(matches[0], 10);
                    
                    // Crea un elemento <span> para mostrar el contador
                    const timerSpan = document.createElement('span');
                    timerSpan.id = 'throttle-timer';
                    timerSpan.style.fontWeight = 'bold';
                    timerSpan.textContent = seconds;

                    // Reemplaza el número estático con nuestro contador dinámico
                    throttleErrorListItem.innerHTML = originalText.replace(/\d+/, timerSpan.outerHTML);
                    
                    const timer = setInterval(() => {
                        seconds--;
                        // Actualiza el número en el <span>
                        document.getElementById('throttle-timer').textContent = seconds;

                        // Cuando el contador llega a 0, detén el intervalo
                        if (seconds <= 0) {
                            clearInterval(timer);
                            // Opcional: Cambia el mensaje cuando el tiempo termina
                            throttleErrorListItem.innerHTML = "Puedes intentar iniciar sesión de nuevo.";
                        }
                    }, 1000); // Se ejecuta cada 1000 milisegundos (1 segundo)
                }
            }
        });
    </script>
</body>
</html>