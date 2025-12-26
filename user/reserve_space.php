<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    // Seleccionamos las oficinas/espacios grandes
    $stmt = $pdo->query("SELECT * FROM espacios");
    $oficinas = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot | Seleccionar Oficina</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (Tus estilos CSS aquí...) */
        :root { --primary: #6366f1; --bg: #030712; --surface: rgba(17, 24, 39, 0.7); --border: rgba(255, 255, 255, 0.1); --text-main: #f9fafb; --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%); }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--text-main); display: flex; }
        main { flex: 1; margin-left: 290px; padding: 4rem; }
        .space-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2.5rem; }
        .space-card { background: var(--surface); border: 1px solid var(--border); border-radius: 35px; overflow: hidden; transition: 0.4s; cursor: pointer; }
        .space-card:hover { transform: translateY(-10px); border-color: var(--primary); }
        .card-img { height: 200px; background-size: cover; background-position: center; }
        .card-body { padding: 2rem; }
        .btn-reserve { width: 100%; padding: 15px; background: var(--gradient); border: none; border-radius: 15px; color: white; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <?php include '../includes/sidebar_user.php'; ?>
    <main>
        <div style="margin-bottom: 3rem;">
            <h1 style="font-family:'Outfit'; font-size: 3rem;">Elegí tu Oficina 🚀</h1>
            <p style="color:#9ca3af;">Seleccioná un sector para ver el mapa de puestos disponibles.</p>
        </div>

        <div class="space-grid">
            <?php foreach ($oficinas as $of): ?>
            <div class="space-card" onclick="location.href='map_reserve.php?espacio_id=<?= $of['id'] ?>'">
                <div class="card-img" style="background-image: url('<?= $of['imagen_url'] ?: 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800' ?>');"></div>
                <div class="card-body">
                    <span style="color:var(--primary); font-size:0.7rem; font-weight:800; text-transform:uppercase;"><?= $of['tipo'] ?></span>
                    <h3 style="font-family:'Outfit'; font-size:1.6rem; margin: 10px 0;"><?= htmlspecialchars($of['nombre']) ?></h3>
                    <p style="color:#9ca3af; font-size:0.9rem; margin-bottom:1.5rem;">Piso <?= $of['piso'] ?> • Sector de alta conectividad.</p>
                    <button class="btn-reserve">Ver Puestos Libres</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>