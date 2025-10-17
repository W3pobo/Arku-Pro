<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - ArkuPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-main: #121826;
            --bg-secondary: #2A3241;
            --accent: #7E57C2;
            --accent-glow: rgba(126, 87, 194, 0.4);
            --text-main: #F0F2F5;
            --text-secondary: #A9B4C7;
            --hover-accent: #6a4da2;
            --gradient-primary: linear-gradient(135deg, #7E57C2 0%, #6a4da2 100%);
            --gradient-secondary: linear-gradient(135deg, #2A3241 0%, #1e2532 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--bg-main) 0%, #0d1117 100%);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Partículas de fondo animadas */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .particle:nth-child(1) { width: 8px; height: 8px; top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 12px; height: 12px; top: 60%; left: 80%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 6px; height: 6px; top: 80%; left: 20%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 10px; height: 10px; top: 30%; left: 70%; animation-delay: 3s; }
        .particle:nth-child(5) { width: 7px; height: 7px; top: 70%; left: 40%; animation-delay: 4s; }
        
        .welcome-container {
            text-align: center;
            background: var(--gradient-secondary);
            border-radius: 24px;
            padding: 60px 50px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                0 0 50px var(--accent-glow);
            max-width: 580px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .welcome-container:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 35px 60px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.08),
                0 0 70px var(--accent-glow);
        }

        .welcome-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(126, 87, 194, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .welcome-container:hover::before {
            left: 100%;
        }
        
        .welcome-logo {
            font-weight: 800;
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--text-main);
            background: linear-gradient(135deg, var(--text-main) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .welcome-logo::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }
        
        .welcome-subtitle {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 500;
        }
        
        .welcome-buttons {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            margin-bottom: 3rem;
        }
        
        .welcome-btn {
            padding: 18px 32px;
            border-radius: 14px;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            display: inline-block;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s ease;
        }

        .welcome-btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 8px 25px rgba(126, 87, 194, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 35px rgba(126, 87, 194, 0.5);
        }

        .btn-primary:active {
            transform: translateY(-2px) scale(1.01);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--text-secondary);
            color: var(--text-secondary);
            position: relative;
            z-index: 1;
        }
        
        .btn-outline::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: var(--gradient-primary);
            transition: width 0.4s ease;
            z-index: -1;
            border-radius: 12px;
        }
        
        .btn-outline:hover {
            border-color: transparent;
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(126, 87, 194, 0.3);
        }

        .btn-outline:hover::after {
            width: 100%;
        }
        
        .welcome-features {
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 2rem;
            text-align: left;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-secondary);
            font-size: 1rem;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature:hover {
            background: rgba(126, 87, 194, 0.1);
            transform: translateY(-3px);
            border-color: rgba(126, 87, 194, 0.2);
        }
        
        .feature-icon {
            font-size: 1.6rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(126, 87, 194, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .feature:hover .feature-icon {
            background: rgba(126, 87, 194, 0.2);
            transform: scale(1.1);
        }

        .feature-text {
            font-weight: 500;
        }

        /* Efectos de brillo adicionales */
        .glow-effect {
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.3;
        }

        .glow-1 {
            top: -50px;
            right: -50px;
            animation: pulse 4s ease-in-out infinite;
        }

        .glow-2 {
            bottom: -50px;
            left: -50px;
            animation: pulse 4s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 40px 30px;
            }
            
            .welcome-logo {
                font-size: 3rem;
            }
            
            .welcome-subtitle {
                font-size: 1.1rem;
            }
            
            .welcome-features {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .feature {
                justify-content: center;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .welcome-container {
                padding: 30px 20px;
            }
            
            .welcome-logo {
                font-size: 2.5rem;
            }
            
            .welcome-btn {
                padding: 16px 24px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Partículas de fondo -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Efectos de brillo -->
    <div class="glow-effect glow-1"></div>
    <div class="glow-effect glow-2"></div>

    <div class="welcome-container">
        <div class="welcome-logo">Arku<span>Pro</span></div>
        <p class="welcome-subtitle">Maximiza tu productividad y enfócate en lo que realmente importa.</p>
        
        <div class="welcome-buttons">
            <a href="{{ route('login') }}" class="welcome-btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
            <a href="{{ route('register') }}" class="welcome-btn btn-outline">
                <i class="fas fa-user-plus"></i> Crear Cuenta
            </a>
        </div>
        
        <div class="welcome-features">
            <div class="feature">
                <span class="feature-icon">⏱️</span>
                <span class="feature-text">Trackeo de tiempo</span>
            </div>
            <div class="feature">
                <span class="feature-icon">📊</span>
                <span class="feature-text">Reportes detallados</span>
            </div>
            <div class="feature">
                <span class="feature-icon">🎯</span>
                <span class="feature-text">Metas de productividad</span>
            </div>
            <div class="feature">
                <span class="feature-icon">🔔</span>
                <span class="feature-text">Notificaciones</span>
            </div>
        </div>
    </div>

    <script>
        // Efecto de escritura para el subtítulo
        document.addEventListener('DOMContentLoaded', function() {
            const subtitle = document.querySelector('.welcome-subtitle');
            const originalText = subtitle.textContent;
            subtitle.textContent = '';
            
            let i = 0;
            function typeWriter() {
                if (i < originalText.length) {
                    subtitle.textContent += originalText.charAt(i);
                    i++;
                    setTimeout(typeWriter, 50);
                }
            }
            
            setTimeout(typeWriter, 1000);
        });
    </script>
</body>
</html>
