<?php
require_once '../db.php';
session_start();

// 1. Protección de sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$accesoActivo = false;
$codigoAcceso = "";

try {
    // 2. Obtener datos básicos del usuario
    $stmtUser = $pdo->prepare("SELECT id, nivel_acceso, nombre FROM usuarios WHERE id = ?");
    $stmtUser->execute([$userId]);
    $user = $stmtUser->fetch();

    if ($user) {
        // Generar código visual (Ej: #WS-101-ELITE)
        $codigoAcceso = "#WS-" . $user['id'] . "-" . strtoupper($user['nivel_acceso']);
        
        // 3. Verificar si tiene una reserva activa AHORA MISMO
        $stmtCheck = $pdo->prepare("
            SELECT id FROM reservas 
            WHERE usuario_id = ? 
            AND fecha_reserva = CURRENT_DATE 
            AND CURRENT_TIME BETWEEN hora_inicio AND hora_fin 
            AND estado = 'confirmada'
            LIMIT 1
        ");
        $stmtCheck->execute([$userId]);
        
        if ($stmtCheck->rowCount() > 0) {
            $accesoActivo = true;
        }
    }

    // 4. Datos para el QR (ID + Timestamp para que sea único)
    $qrData = "USER_ID:" . $userId . "|CODE:" . $codigoAcceso . "|TIME:" . time();
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);

} catch (PDOException $e) {
    die("Error en el sistema de acceso: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Mi Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #030712;
            --success: #10b981;
            --danger: #ef4444;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg); 
            color: white; 
            margin: 0; 
            display: flex; 
            min-height: 100vh;
            background-image: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
        }

        main { 
            flex: 1; 
            margin-left: 260px; /* Ancho ajustado al sidebar */
            padding: 3rem; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
        }

        .qr-card { 
            background: rgba(255,255,255,0.02); 
            border: 1px solid var(--border); 
            padding: 4rem; 
            border-radius: 50px; 
            text-align: center; 
            backdrop-filter: blur(30px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.5);
            max-width: 450px;
            width: 100%;
        }

        .qr-box { 
            background: white; 
            padding: 2rem; 
            border-radius: 35px; 
            margin: 2.5rem 0; 
            display: inline-block;
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.2);
        }

        .qr-box img { display: block; }

        .status-indicator {
            margin-top: 25px;
            font-weight: 800;
            font-family: 'Outfit';
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 15px;
            background: rgba(255,255,255,0.03);
        }

        .status-active { color: var(--success); }
        .status-inactive { color: var(--danger); }
        
        .pulse {
            width: 10px; height: 10px; border-radius: 50%;
            animation: pulse-animation 2s infinite;
        }
        @keyframes pulse-animation {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>
<body>

    <?php include '../includes/sidebar_user.php'; ?>

    <main>
        <div class="qr-card">
            <h1 style="font-family: 'Outfit'; font-size: 2.5rem; margin: 0;">Pase Digital</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Escaneá en el tótem de entrada para ingresar a WorkSpot.</p>
            
            <div class="qr-box">
                <img src="<?= $qrUrl ?>" alt="Código de Acceso QR">
            </div>

            <div style="font-family:'Outfit'; font-size:1.8rem; letter-spacing:3px; color: var(--primary); font-weight: 800;">
                <?= $codigoAcceso ?>
            </div>

            <?php if ($accesoActivo): ?>
                <div class="status-indicator status-active">
                    <div class="pulse" style="background: var(--success);"></div>
                    <i class="fas fa-check-circle"></i> ACCESO AUTORIZADO
                </div>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 15px;">Tu reserva actual está en curso.</p>
            <?php else: ?>
                <div class="status-indicator status-inactive">
                    <i class="fas fa-times-circle"></i> SIN RESERVA ACTIVA
                </div>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 15px;">Necesitás una reserva vigente para ingresar.</p>
                <a href="reserve_space.php" style="color: var(--primary); font-size: 0.8rem; text-decoration: none; font-weight: 700;">Reservar ahora &rarr;</a>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>