<?php
require_once '../db.php';
session_start();

// 1. Protección de sesión: Si no hay usuario logueado, al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'proximas';

try {
    // 2. Cálculo de Estadísticas Reales
    // Calculamos horas del mes, reservas activas y total de créditos invertidos
    $stmtStats = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN MONTH(fecha_reserva) = MONTH(CURRENT_DATE) AND YEAR(fecha_reserva) = YEAR(CURRENT_DATE) THEN TIMESTAMPDIFF(HOUR, hora_inicio, hora_fin) ELSE 0 END) as horas_mes,
            COUNT(CASE WHEN estado IN ('confirmada', 'pendiente') AND fecha_reserva >= CURRENT_DATE THEN 1 END) as activas,
            SUM(total_pago) as total_creditos
        FROM reservas 
        WHERE usuario_id = ?
    ");
    $stmtStats->execute([$userId]);
    $stats = $stmtStats->fetch();

    // 3. Lógica de Filtrado por Pestañas
    $whereClause = "WHERE r.usuario_id = ?";
    if ($tab == 'pasadas') {
        $whereClause .= " AND (r.fecha_reserva < CURRENT_DATE OR r.estado = 'completada')";
    } elseif ($tab == 'canceladas') {
        $whereClause .= " AND r.estado = 'cancelada'";
    } else {
        // Por defecto: Próximas
        $whereClause .= " AND r.fecha_reserva >= CURRENT_DATE AND r.estado IN ('confirmada', 'pendiente')";
    }

    // 4. CONSULTA MAESTRA (Corregida con todos los JOINS jerárquicos)
    $stmtRes = $pdo->prepare("
        SELECT 
            r.*, 
            p.nombre as puesto_nombre, 
            i.nombre as isla_nombre, 
            e.nombre as espacio_nombre, 
            e.piso, 
            e.tipo,
            e.id as espacio_id
        FROM reservas r 
        JOIN puestos p ON r.puesto_id = p.id 
        JOIN islas i ON p.isla_id = i.id 
        JOIN espacios e ON i.espacio_id = e.id 
        $whereClause
        ORDER BY r.fecha_reserva ASC, r.hora_inicio ASC
    ");
    $stmtRes->execute([$userId]);
    $reservas = $stmtRes->fetchAll();

} catch (PDOException $e) {
    die("Error al cargar tus reservas: " . $e->getMessage());
}

// Función auxiliar para iconos según tipo de oficina
function getIcon($tipo) {
    switch ($tipo) {
        case 'Meeting Room': return 'fa-bolt';
        case 'Private Office': return 'fa-briefcase';
        default: return 'fa-desktop';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Mis Reservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1; --secondary: #0ea5e9; --bg: #030712;
            --surface: rgba(17, 24, 39, 0.7); --border: rgba(255, 255, 255, 0.1);
            --text-main: #f9fafb; --text-muted: #9ca3af;
            --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
            --glow: rgba(99, 102, 241, 0.3); --success: #10b981;
            --warning: #f59e0b; --danger: #ef4444; --accent: #00f2ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--text-main); display: flex; min-height: 100vh; }
        
        .aurora { position: fixed; top: -100px; right: -100px; width: 50vw; height: 50vw; background: var(--gradient); filter: blur(150px); opacity: 0.1; z-index: -1; }
        
        main { flex: 1; margin-left: 260px; padding: 4rem; }

        .header-section { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-section h1 { font-family: 'Outfit'; font-size: 3rem; letter-spacing: -1.5px; margin-bottom: 0.5rem; }

        .btn-new { background: var(--gradient); color: white; border: none; padding: 14px 28px; border-radius: 18px; font-family: 'Outfit'; font-weight: 700; cursor: pointer; box-shadow: 0 10px 20px var(--glow); transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
        .btn-new:hover { transform: translateY(-3px); opacity: 0.9; }

        .reserve-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); padding: 1.8rem; border-radius: 28px; backdrop-filter: blur(20px); }
        .stat-card p { font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
        .stat-card h2 { font-family: 'Outfit'; font-size: 2.2rem; margin: 0; }
        .stat-card h2 span { font-size: 1rem; color: var(--accent); opacity: 0.8; }

        .filter-tabs { display: flex; gap: 12px; margin-bottom: 3rem; }
        .tab-btn { padding: 10px 22px; border-radius: 50px; border: 1px solid var(--border); background: var(--surface); color: var(--text-muted); font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .tab-btn.active { border-color: var(--primary); color: white; background: rgba(99, 102, 241, 0.1); }

        .reserve-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2.5rem; }
        .reserve-card { background: var(--surface); border: 1px solid var(--border); border-radius: 35px; padding: 2.2rem; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); backdrop-filter: blur(20px); display: flex; flex-direction: column; justify-content: space-between; }
        .reserve-card:hover { transform: translateY(-12px); border-color: var(--primary); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); }

        .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; }
        .icon-circle { width: 54px; height: 54px; border-radius: 18px; background: rgba(255,255,255,0.03); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--primary); border: 1px solid var(--border); }
        
        .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; border: 1px solid transparent; }
        .status-badge.confirmada { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: var(--success); }
        .status-badge.pendiente { background: rgba(245, 158, 11, 0.1); color: var(--warning); border-color: var(--warning); }
        .status-badge.completada { background: rgba(255,255,255,0.05); color: var(--text-muted); border-color: var(--border); }
        .status-badge.cancelada { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: var(--danger); }

        .card-content h3 { font-family: 'Outfit'; font-size: 1.6rem; margin: 0 0 8px 0; }
        .card-content p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; }

        .time-box { background: rgba(255,255,255,0.02); border-radius: 20px; padding: 1.2rem; margin-bottom: 1.5rem; border: 1px solid var(--border); }
        .time-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 0.9rem; }
        .time-row i { color: var(--secondary); width: 15px; text-align: center; }

        .card-actions { display: flex; gap: 10px; border-top: 1px solid var(--border); padding-top: 1.5rem; }
        .action-btn { flex: 1; padding: 12px; border-radius: 15px; border: 1px solid var(--border); background: rgba(255,255,255,0.03); color: var(--text-main); cursor: pointer; font-weight: 700; font-size: 0.85rem; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Outfit'; text-decoration: none; }
        .action-btn:hover { border-color: var(--primary); background: rgba(99, 102, 241, 0.1); color: white; }
        .action-btn.danger:hover { border-color: var(--danger); background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    </style>
</head>
<body>

    <div class="aurora"></div>
    
    <?php include '../includes/sidebar_user.php'; ?>

    <main>
        <div class="header-section">
            <div>
                <p style="color: var(--primary); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; margin-bottom: 5px;">Gestión de Espacios</p>
                <h1>Mis Reservas</h1>
            </div>
            <a href="reserve_space.php" class="btn-new">
                <i class="fas fa-plus"></i> Nueva Reserva
            </a>
        </div>

        <div class="reserve-stats">
            <div class="stat-card">
                <p>Uso este Mes</p>
                <h2><?= number_format($stats['horas_mes'] ?? 0, 0) ?> <span>horas</span></h2>
            </div>
            <div class="stat-card">
                <p>Reservas Activas</p>
                <h2 style="color: var(--accent);"><?= str_pad($stats['activas'], 2, "0", STR_PAD_LEFT) ?></h2>
            </div>
            <div class="stat-card">
                <p>Créditos Invertidos</p>
                <h2><?= number_format(($stats['total_creditos'] ?? 0) / 1000, 1) ?>k <span>pts</span></h2>
            </div>
        </div>

        <div class="filter-tabs">
            <a href="?tab=proximas" class="tab-btn <?= $tab == 'proximas' ? 'active' : '' ?>">Próximas</a>
            <a href="?tab=pasadas" class="tab-btn <?= $tab == 'pasadas' ? 'active' : '' ?>">Pasadas</a>
            <a href="?tab=canceladas" class="tab-btn <?= $tab == 'canceladas' ? 'active' : '' ?>">Canceladas</a>
        </div>

        <div class="reserve-grid">
            <?php if (empty($reservas)): ?>
                <p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 4rem;">
                    No se encontraron reservas en esta categoría.
                </p>
            <?php else: ?>
                <?php foreach ($reservas as $r): ?>
                    <div class="reserve-card" <?= $tab == 'pasadas' ? 'style="opacity: 0.8;"' : '' ?>>
                        <div class="card-top">
                            <div class="icon-circle">
                                <i class="fas <?= getIcon($r['tipo']) ?>"></i>
                            </div>
                            <span class="status-badge <?= strtolower($r['estado']) ?>"><?= ucfirst($r['estado']) ?></span>
                        </div>
                        <div class="card-content">
                            <h3><?= htmlspecialchars($r['puesto_nombre']) ?></h3>
                            <p><?= htmlspecialchars($r['espacio_nombre']) ?> • Piso <?= $r['piso'] ?></p>
                            <div class="time-box">
                                <div class="time-row"><i class="fas fa-calendar-day"></i> <b><?= date('d M Y', strtotime($r['fecha_reserva'])) ?></b></div>
                                <div class="time-row"><i class="fas fa-clock"></i> <span><?= substr($r['hora_inicio'], 0, 5) ?> - <?= substr($r['hora_fin'], 0, 5) ?></span></div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <?php if ($tab == 'proximas'): ?>
                                <a href="map_reserve.php?espacio_id=<?= $r['espacio_id'] ?>" class="action-btn">
                                    <i class="fas fa-map-marked-alt"></i> Mapa
                                </a>
                                <button class="action-btn danger"><i class="fas fa-times"></i> Cancelar</button>
                            <?php elseif ($tab == 'pasadas'): ?>
                                <button class="action-btn"><i class="fas fa-file-invoice"></i> Ticket</button>
                                <a href="map_reserve.php?espacio_id=<?= $r['espacio_id'] ?>" class="action-btn"><i class="fas fa-redo"></i> Repetir</a>
                            <?php else: ?>
                                <a href="reserve_space.php" class="action-btn"><i class="fas fa-redo"></i> Reintentar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>