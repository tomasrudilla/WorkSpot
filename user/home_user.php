<?php 
require_once '../db.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$nowTime = date('H:i:s');
$nowDate = date('Y-m-d');

// --- LÓGICA PARA FINALIZAR RESERVA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'finalizar') {
    $reservaId = $_POST['reserva_id'];
    $puestoId = $_POST['puesto_id'];

    try {
        $pdo->beginTransaction();

        // 1. Marcar la reserva como 'completada' y ajustar la hora de fin al momento actual
        $stmtEndRes = $pdo->prepare("UPDATE reservas SET estado = 'completada', hora_fin = CURRENT_TIME WHERE id = ?");
        $stmtEndRes->execute([$reservaId]);

        // 2. Liberar el puesto para que otros puedan usarlo
        $stmtFreePuesto = $pdo->prepare("UPDATE puestos SET estado = 'disponible' WHERE id = ?");
        $stmtFreePuesto->execute([$puestoId]);

        $pdo->commit();
        header("Location: home_user.php?status=finalizada");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_msg = "Error al finalizar: " . $e->getMessage();
    }
}

try {
    // 1. Obtener datos del Usuario
    $stmtUser = $pdo->prepare("SELECT nombre, nivel_acceso, creditos FROM usuarios WHERE id = ?");
    $stmtUser->execute([$userId]);
    $userData = $stmtUser->fetch();
    
    $userName = $userData['nombre'] ?? "Usuario";
    $userLevel = "Miembro " . ($userData['nivel_acceso'] ?? "Basic");
    $userCredits = $userData['creditos'] ?? 0;

    // 2. BUSCAR RESERVA ACTIVA (Agregamos r.id para poder finalizarla)
    $stmtRes = $pdo->prepare("
        SELECT 
            r.id as reserva_id,
            p.id as puesto_id, 
            p.nombre as puesto_nombre, 
            i.nombre as isla_nombre, 
            i.id as isla_id,
            e.nombre as oficina_nombre
        FROM reservas r
        JOIN puestos p ON r.puesto_id = p.id
        JOIN islas i ON p.isla_id = i.id
        JOIN espacios e ON i.espacio_id = e.id
        WHERE r.usuario_id = ? 
        AND r.fecha_reserva = ? 
        AND ? BETWEEN r.hora_inicio AND r.hora_fin
        AND r.estado = 'confirmada'
        LIMIT 1
    ");
    $stmtRes->execute([$userId, $nowDate, $nowTime]);
    $activeRes = $stmtRes->fetch();

    if ($activeRes) {
        $currentIsland = $activeRes['isla_nombre'];
        $currentSeat = $activeRes['puesto_nombre'];
        $islaId = $activeRes['isla_id'];
        $puestoId = $activeRes['puesto_id'];
        $reservaId = $activeRes['reserva_id'];

        // 3. Cargar Hardware del PUESTO asignado
        $stmtHard = $pdo->prepare("SELECT nombre, icono, estado_dispositivo FROM hardware WHERE puesto_id = ?");
        $stmtHard->execute([$puestoId]);
        $hardwareList = $stmtHard->fetchAll();

        // 4. Cargar Compañeros de la misma ISLA
        $stmtTeam = $pdo->prepare("
            SELECT u.nombre, p.nombre as puesto
            FROM reservas r
            JOIN usuarios u ON r.usuario_id = u.id
            JOIN puestos p ON r.puesto_id = p.id
            WHERE p.isla_id = ? 
            AND r.fecha_reserva = ? 
            AND u.id != ?
            AND r.estado = 'confirmada'
        ");
        $stmtTeam->execute([$islaId, $nowDate, $userId]);
        $islandTeam = $stmtTeam->fetchAll();
    } else {
        $currentIsland = "Ninguna";
        $currentSeat = "Sin asignar";
        $hardwareList = [];
        $islandTeam = [];
    }

} catch (PDOException $e) {
    die("Error crítico en el sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Digital Command Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1; --primary-glow: rgba(99, 102, 241, 0.4); --accent: #00f2ff;
            --bg: #030712; --surface: rgba(15, 23, 42, 0.5); --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc; --text-muted: #94a3b8; --success: #10b981;
            --danger: #ef4444; --sidebar-width: 290px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }
        main { flex: 1; margin-left: var(--sidebar-width); padding: 2.5rem 3.5rem; transition: all 0.3s ease; animation: fadeIn 0.8s ease-out; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .live-status-bar { display: flex; justify-content: space-between; align-items: center; background: rgba(255, 255, 255, 0.02); border-bottom: 1px solid var(--border); margin: -2.5rem -3.5rem 2.5rem -3.5rem; padding: 1rem 3.5rem; backdrop-filter: blur(20px); }
        .live-item { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
        .live-indicator { width: 8px; height: 8px; border-radius: 50%; background: var(--success); box-shadow: 0 0 10px var(--success); animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        .welcome-section h1 { font-family: 'Outfit'; font-size: 3rem; letter-spacing: -1.5px; margin-bottom: 5px; }
        .layout-grid { display: grid; grid-template-columns: 1.8fr 1fr; gap: 2.5rem; margin-top: 2rem; }

        .puesto-card { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border-radius: 40px; padding: 3rem; border: 1px solid rgba(255, 255, 255, 0.1); position: relative; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.4); }
        .meta-pill { background: rgba(0, 242, 255, 0.15); color: var(--accent); padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; border: 1px solid var(--accent); }

        .island-section, .hardware-card { background: var(--surface); border: 1px solid var(--border); border-radius: 35px; padding: 2.5rem; }
        .team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; }
        .member-card { background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); padding: 1.5rem; border-radius: 25px; display: flex; flex-direction: column; align-items: center; }
        .member-avatar { width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: white; margin-bottom: 1rem; background: var(--primary); }

        .device-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--border); }
        .wallet-widget { background: linear-gradient(180deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%); border: 1px solid var(--border); border-radius: 40px; padding: 2.5rem; text-align: center; }
        .btn-primary { width: 100%; padding: 18px; background: var(--primary); color: white; border: none; border-radius: 20px; font-weight: 800; cursor: pointer; transition: 0.3s; }

        @media (max-width: 1200px) { .layout-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <?php include '../includes/sidebar_user.php'; ?>

    <main>
        <div class="live-status-bar">
            <div style="display: flex; gap: 30px;">
                <div class="live-item"><i class="fas fa-clock"></i> <span id="clock">--:--:--</span></div>
                <div class="live-item"><i class="fas fa-wifi"></i> <span>WS-Enterprise-A (850Mbps)</span></div>
            </div>
            <div class="live-item">
                <div class="live-indicator"></div>
                <span style="color: var(--success); font-size: 0.7rem;">Conexión Segura Activa</span>
            </div>
        </div>

        <div class="welcome-section">
            <h1 id="greeting">¡Hola, <?php echo htmlspecialchars(explode(' ', $userName)[0]); ?>! 👋</h1>
            <p>Tu ubicación actual es <b><?php echo htmlspecialchars($currentSeat); ?></b> en la <b><?php echo htmlspecialchars($currentIsland); ?></b>.</p>
        </div>

        <div class="layout-grid">
            <div class="left-col">
                <div class="puesto-card">
                    <div class="puesto-meta">
                        <span class="meta-pill"><?php echo $activeRes ? "Sesión Activa" : "Sin Reserva"; ?></span>
                    </div>
                    <h2 style="font-family:'Outfit'; font-size:3.5rem; margin: 15px 0; line-height:1;"><?php echo htmlspecialchars($currentIsland); ?></h2>
                    <p style="font-size:1.4rem; opacity:0.8; margin-bottom:2.5rem;"><?php echo htmlspecialchars($currentSeat); ?> • Workspace</p>
                    <div style="display:flex; gap:15px;">
                        <button style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:white; padding:15px 25px; border-radius:15px; cursor:pointer;" onclick="location.href='my_access.php'"><i class="fas fa-key"></i> Abrir con NFC</button>
                        
                        <?php if ($activeRes): ?>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="action" value="finalizar">
                                <input type="hidden" name="reserva_id" value="<?php echo $reservaId; ?>">
                                <input type="hidden" name="puesto_id" value="<?php echo $puestoId; ?>">
                                <button type="submit" style="background:rgba(239,68,68,0.1); border:1px solid var(--danger); color:var(--danger); padding:15px 25px; border-radius:15px; cursor:pointer;" onclick="return confirm('¿Estás seguro de que deseas finalizar tu turno ahora?')">
                                    Finalizar
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="island-section" style="margin-top: 2.5rem;">
                    <h3 style="font-family: 'Outfit'; margin-bottom: 2rem;">Compañeros de Isla</h3>
                    <div class="team-grid">
                        <?php if(empty($islandTeam)): ?>
                            <p style="color: var(--text-muted);">No hay otros compañeros en esta isla hoy.</p>
                        <?php else: ?>
                            <?php foreach($islandTeam as $member): ?>
                            <div class="member-card">
                                <div class="member-avatar">
                                    <?php 
                                        $parts = explode(' ', $member['nombre']);
                                        echo strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                                    ?>
                                </div>
                                <div style="font-weight:700; font-size:0.9rem;"><?php echo htmlspecialchars($member['nombre']); ?></div>
                                <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; margin-top:5px;"><?php echo htmlspecialchars($member['puesto']); ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="right-col">
                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    <div class="hardware-card">
                        <h3 style="font-family: 'Outfit'; margin-bottom: 1.5rem;">Hardware del Puesto</h3>
                        <?php if(empty($hardwareList)): ?>
                            <p style="color: var(--text-muted); font-size: 0.85rem;">No hay hardware registrado para este puesto.</p>
                        <?php else: ?>
                            <?php foreach($hardwareList as $item): ?>
                            <div class="device-item">
                                <div style="display:flex; align-items:center; gap:15px;">
                                    <div style="color:var(--primary);"><i class="<?php echo $item['icono']; ?>"></i></div>
                                    <span style="font-weight:600; font-size:0.9rem;"><?php echo htmlspecialchars($item['nombre']); ?></span>
                                </div>
                                <div style="width:35px; height:18px; background:var(--success); border-radius:20px; position:relative;">
                                    <div style="width:12px; height:12px; background:white; border-radius:50%; position:absolute; right:3px; top:3px;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="wallet-widget">
                        <p style="color: var(--text-muted); font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">Créditos Disponibles</p>
                        <h2 style="font-family: 'Outfit'; font-size: 4rem; margin: 10px 0;"><?php echo number_format($userCredits, 0); ?> <span style=\"font-size: 1.5rem; color: var(--accent);\">pts</span></h2>
                        <button class="btn-primary" onclick="location.href='reserve_space.php'">Cargar más</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('es-AR', { hour12: false });
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>