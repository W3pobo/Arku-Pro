<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Arquitectura Pro</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.js"></script>
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --success: #2ecc71;
            --light: #ecf0f1;
            --dark: #34495e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .welcome-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
        }
        
        .welcome-logo {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .welcome-logo span {
            color: #3498db;
        }
        
        .welcome-title {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .welcome-subtitle {
            font-size: 1.1rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        .welcome-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .welcome-btn {
            padding: 15px 30px;
            border-radius: 50px;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }
        
        .btn-outline:hover {
            background-color: white;
            color: var(--primary);
            transform: translateY(-2px);
        }
        
        .welcome-features {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            text-align: left;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .feature-icon {
            font-size: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .welcome-container {
                padding: 30px;
            }
            
            .welcome-logo {
                font-size: 2.5rem;
            }
            
            .welcome-title {
                font-size: 1.8rem;
            }
            
            .welcome-features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="welcome-container">
            <div class="welcome-logo">
                Arku<span>Pro</span>
            </div>
            
            <h1 class="welcome-title">Arquitectura Pro</h1>
            <p class="welcome-subtitle">Dedica esfuerzo a tu productividad</p>
            
            <div class="welcome-buttons">
                <a href="{{ route('login') }}" class="welcome-btn btn-primary">
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="welcome-btn btn-outline">
                    Crear Cuenta
                </a>
            </div>
            
            <div class="welcome-features">
                <div class="feature">
                    <span class="feature-icon">⏱️</span>
                    <span>Trackeo de tiempo</span>
                </div>
                <div class="feature">
                    <span class="feature-icon">📊</span>
                    <span>Reportes detallados</span>
                </div>
                <div class="feature">
                    <span class="feature-icon">🎯</span>
                    <span>Metas de productividad</span>
                </div>
                <div class="feature">
                    <span class="feature-icon">🔔</span>
                    <span>Notificaciones inteligentes</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            setup() {
                // Aquí puedes agregar interactividad si es necesario
                return {};
            }
        }).mount('#app');
    </script>
</body>
</html>