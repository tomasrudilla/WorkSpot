<?php
require_once 'db.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        // Buscamos al usuario por email
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verificamos si existe y si la contraseña coincide con el hash
        if ($user && password_verify($password, $user['password'])) {
            // Guardamos datos esenciales en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];

            // Redirección según el rol
            if ($user['rol'] === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: home_user.php");
            }
            exit;
        } else {
            $error = "Email o contraseña incorrectos.";
        }
    } else {
        $error = "Por favor, completá todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pro | WorkSpot</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg: #020617;
            --surface: rgba(15, 23, 42, 0.6);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #6366f1;
            --secondary: #0ea5e9;
            --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
            --glow: rgba(99, 102, 241, 0.2);
            --error: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .aurora {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(14, 165, 233, 0.15) 0%, transparent 40%);
            z-index: -1;
        }

        .main-wrapper {
            width: 100%;
            max-width: 1100px;
            padding: 2rem;
            z-index: 10;
        }

        .split-card {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 32px;
            overflow: hidden;
            backdrop-filter: blur(25px);
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.5);
        }

        .info-side {
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid var(--border);
        }

        .info-content h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 800;
        }

        .feature-list { list-style: none; }
        .feature-item { 
            display: flex; align-items: center; gap: 12px; margin-bottom: 1rem; 
            color: var(--text-muted); font-size: 0.95rem;
        }
        .feature-item i { color: var(--primary); }

        .form-side {
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 3rem;
            display: flex;
            align-items: center; gap: 10px;
        }
        .logo i { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        h2 { font-family: 'Outfit'; font-size: 1.8rem; margin-bottom: 0.5rem; }
        .subtitle { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2.5rem; }

        /* Estilo Alerta Error */
        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--error);
            color: var(--error);
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group { margin-bottom: 1.5rem; position: relative; }
        .form-group i {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); transition: color 0.3s;
        }

        input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.07);
            border-color: var(--primary);
            box-shadow: 0 0 20px var(--glow);
        }

        input:focus + i { color: var(--primary); }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            border-radius: 14px;
            border: none;
            background: var(--gradient);
            color: white;
            font-family: 'Outfit';
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            box-shadow: 0 15px 30px var(--glow);
            transition: transform 0.3s, opacity 0.3s;
        }

        .btn-primary:hover { transform: translateY(-2px); opacity: 0.95; }

        .footer-text {
            text-align: center; margin-top: 2.5rem;
            font-size: 0.9rem; color: var(--text-muted);
        }
        .footer-text a { color: var(--primary); text-decoration: none; font-weight: 600; }

        @media (max-width: 968px) {
            .split-card { grid-template-columns: 1fr; }
            .info-side { display: none; }
            .main-wrapper { max-width: 500px; }
            .form-side { padding: 3rem 2rem; }
        }
    </style>
</head>
<body>

    <div class="aurora"></div>

    <div class="main-wrapper">
        <div class="split-card">
            
            <div class="info-side">
                <div class="info-header">
                    <div class="logo"><i class="fas fa-terminal"></i> WorkSpot</div>
                </div>
                <div class="info-content">
                    <h1>Impulsá tu <br><span style="color: var(--primary)">Productividad.</span></h1>
                    <ul class="feature-list">
                        <li class="feature-item"><i class="fas fa-check-circle"></i> Gestión de espacios en tiempo real</li>
                        <li class="feature-item"><i class="fas fa-check-circle"></i> Acceso biométrico integrado</li>
                        <li class="feature-item"><i class="fas fa-check-circle"></i> Networking con profesionales</li>
                    </ul>
                </div>
                <div class="info-footer">
                    <p style="font-size: 0.8rem; opacity: 0.6">© 2025 WorkSpot Technology S.A.</p>
                </div>
            </div>

            <div class="form-side">
                <div class="mobile-logo logo" style="display: none;">
                    <i class="fas fa-terminal"></i> WorkSpot
                </div>
                
                <h2>Bienvenido de nuevo</h2>
                <p class="subtitle">Ingresá tus datos para acceder a tu panel.</p>

                <?php if ($error): ?>
                    <div class="error-msg">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Correo electrónico" required>
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Contraseña" required>
                        <i class="fas fa-lock"></i>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 0.85rem;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--text-muted);">
                            <input type="checkbox" name="remember" style="width: auto;"> Recordarme
                        </label>
                        <a href="#" style="color: var(--primary); text-decoration: none;">¿Olvidaste la clave?</a>
                    </div>

                    <button type="submit" class="btn-primary">Iniciar Sesión</button>
                </form>

                <p class="footer-text">
                    ¿No tenés cuenta? <a href="registro.php">Registrate gratis</a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>