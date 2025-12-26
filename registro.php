<?php
require_once 'db.php'; // Asegúrate de que db.php esté en el mismo directorio
session_start();

$error = "";
$success = "";

// Lógica de procesamiento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        
        // 1. Verificar si el email ya existe
        $checkEmail = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $checkEmail->execute([$email]);
        
        if ($checkEmail->rowCount() > 0) {
            $error = "Este correo electrónico ya está registrado.";
        } else {
            // 2. Hashear la contraseña por seguridad
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // 3. Insertar el nuevo usuario (Por defecto rol 'user' y nivel 'Basic')
            try {
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, nivel_acceso, creditos) VALUES (?, ?, ?, 'user', 'Basic', 0)");
                $stmt->execute([$nombre, $email, $pass_hash]);
                
                // Éxito: Podés redirigir o mostrar un mensaje
                $success = "¡Cuenta creada con éxito! Redirigiendo al login...";
                header("refresh:3;url=login.php"); // Redirige tras 3 segundos
            } catch (PDOException $e) {
                $error = "Error en el registro: " . $e->getMessage();
            }
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
    <title>Crear Cuenta | WorkSpot</title>
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
            --success: #10b981;
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
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 20% 80%, rgba(14, 165, 233, 0.15) 0%, transparent 40%);
            z-index: -1;
        }

        /* Estilos de Alertas */
        .alert {
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid var(--error); color: var(--error); }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: var(--success); }

        .main-wrapper {
            width: 100%;
            max-width: 1100px;
            padding: 2rem;
            z-index: 10;
        }

        .split-card {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
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
            justify-content: center;
            border-right: 1px solid var(--border);
        }

        .info-side h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 2rem;
        }

        .benefit-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            padding: 1.5rem;
            border-radius: 20px;
            margin-bottom: 1rem;
            transition: 0.3s;
        }

        .benefit-card:hover { background: rgba(255, 255, 255, 0.06); }
        .benefit-card i { color: var(--secondary); margin-bottom: 0.5rem; font-size: 1.2rem; }
        .benefit-card p { font-size: 0.9rem; color: var(--text-muted); }

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
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center; gap: 10px;
        }
        .logo i { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        h1 { font-family: 'Outfit'; font-size: 2rem; margin-bottom: 0.5rem; }
        .subtitle { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2.5rem; }

        .form-group { margin-bottom: 1.2rem; position: relative; }
        .form-group i {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: white;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 20px var(--glow);
        }

        .btn-primary {
            width: 100%;
            padding: 1.1rem;
            border-radius: 14px;
            border: none;
            background: var(--gradient);
            color: white;
            font-family: 'Outfit';
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1.5rem;
            box-shadow: 0 15px 30px var(--glow);
            transition: 0.3s;
        }

        .btn-primary:hover { transform: translateY(-2px); opacity: 0.95; }

        .footer-text {
            text-align: center; margin-top: 2rem;
            font-size: 0.9rem; color: var(--text-muted);
        }
        .footer-text a { color: var(--primary); text-decoration: none; font-weight: 600; }

        .back-home {
            position: absolute; top: 40px; left: 40px;
            color: var(--text-muted); text-decoration: none; font-size: 0.9rem;
            display: flex; align-items: center; gap: 8px;
        }

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

    <a href="index.php" class="back-home">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <div class="main-wrapper">
        <div class="split-card">
            
            <div class="info-side">
                <h2>Llevá tu trabajo al <span style="color: var(--secondary)">siguiente nivel.</span></h2>
                
                <div class="benefit-card">
                    <i class="fas fa-rocket"></i>
                    <p>Acceso instantáneo a todos nuestros espacios premium.</p>
                </div>
                
                <div class="benefit-card">
                    <i class="fas fa-shield-alt"></i>
                    <p>Seguridad de grado empresarial en tus datos.</p>
                </div>

                <div class="benefit-card">
                    <i class="fas fa-users"></i>
                    <p>Unite a una comunidad de +10k profesionales.</p>
                </div>
            </div>

            <div class="form-side">
                <div class="logo">
                    <i class="fas fa-terminal"></i> WorkSpot
                </div>

                <h1>Crear cuenta</h1>
                <p class="subtitle">Completá los datos para empezar tu experiencia.</p>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-times-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="registro.php">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo isset($nombre) ? $nombre : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo isset($email) ? $email : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Contraseña segura" required>
                    </div>

                    <p style="font-size: 0.75rem; color: var(--text-muted); margin: 1rem 0;">
                        Al registrarte, aceptas nuestros <a href="#" style="color: var(--primary)">Términos y Condiciones</a>.
                    </p>

                    <button type="submit" class="btn-primary">Registrarse ahora</button>
                </form>

                <p class="footer-text">
                    ¿Ya tenés cuenta? <a href="login.php">Iniciá sesión</a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>