<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg: #030712; --surface: rgba(17, 24, 39, 0.7); --border: rgba(255, 255, 255, 0.1);
            --text-main: #f9fafb; --text-muted: #9ca3af; --primary: #6366f1; --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
        }
        .light-mode { --bg: #f3f4f6; --surface: rgba(255, 255, 255, 0.9); --border: rgba(0, 0, 0, 0.1); --text-main: #111827; --text-muted: #4b5563; }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--text-main); display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        .aurora { position: fixed; top: 0; left: 50%; transform: translateX(-50%); width: 100vw; height: 100vh; background: var(--gradient); filter: blur(150px); opacity: 0.1; z-index: -1; }
        
        nav { display: flex; justify-content: space-between; padding: 1.5rem 8%; align-items: center; }
        .logo { font-family: 'Outfit'; font-size: 1.5rem; font-weight: 800; text-decoration: none; color: var(--text-main); }

        .auth-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .auth-card { background: var(--surface); backdrop-filter: blur(20px); border: 1px solid var(--border); padding: 3rem; border-radius: 28px; width: 100%; max-width: 450px; }
        
        h2 { font-family: 'Outfit'; font-size: 2rem; margin-bottom: 0.5rem; text-align: center; }
        p.subtitle { color: var(--text-muted); text-align: center; margin-bottom: 2rem; font-size: 0.9rem; }

        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); }
        input { 
            width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); 
            border-radius: 12px; color: var(--text-main); font-size: 1rem; outline: none; transition: 0.3s;
        }
        input:focus { border-color: var(--primary); background: rgba(0,0,0,0.4); }

        .btn-submit { 
            width: 100%; padding: 1rem; border-radius: 12px; border: none; background: var(--gradient); 
            color: white; font-weight: 700; cursor: pointer; margin-top: 1rem; font-family: 'Outfit';
        }
        
        .toggle-auth { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted); }
        .toggle-auth a { color: var(--primary); text-decoration: none; font-weight: 600; }

        /* Reutilizamos controles de la landing */
        .controls { display: flex; gap: 1rem; }
        .theme-toggle { width: 35px; height: 35px; border-radius: 50%; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    </style>
</head>
<body>
    <div class="aurora"></div>
    <nav>
        <a href="index.php" class="logo"><i class="fas fa-terminal"></i> WorkSpot</a>
        <div class="controls">
            <div class="theme-toggle" onclick="document.body.classList.toggle('light-mode')">
                <i class="fas fa-adjust"></i>
            </div>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card" id="login-box">
            <h2>Bienvenido</h2>
            <p class="subtitle">Ingresa tus credenciales para continuar</p>
            <form>
                <div class="form-group">
                    <label>Email Corporativo</label>
                    <input type="email" placeholder="nombre@empresa.com">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" placeholder="••••••••">
                </div>
                <button type="button" class="btn-submit">Acceder al Panel</button>
            </form>
            <p class="toggle-auth">¿No tienes cuenta? <a href="#">Regístrate</a></p>
        </div>
    </div>
</body>
</html>