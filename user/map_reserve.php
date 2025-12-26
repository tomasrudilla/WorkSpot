<?php 
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit; }

$espacioId = isset($_GET['espacio_id']) ? (int)$_GET['espacio_id'] : 1;

try {
    // 1. Obtener oficina
    $stmtEsp = $pdo->prepare("SELECT nombre FROM espacios WHERE id = ?");
    $stmtEsp->execute([$espacioId]);
    $oficina = $stmtEsp->fetch();

    // 2. Obtener Islas
    $stmtIslas = $pdo->prepare("SELECT * FROM islas WHERE espacio_id = ?");
    $stmtIslas->execute([$espacioId]);
    $islas = $stmtIslas->fetchAll();

    // 3. Preparar puestos (Cambio de tabla 'espacios' a 'puestos')
    $stmtPuestos = $pdo->prepare("SELECT id, nombre, estado, precio_hora FROM puestos WHERE isla_id = ?");

} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot | Plano 3D</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6366f1; --free: #10b981; --occupied: #ef4444; --sidebar-width: 260px; }
        body { font-family: 'Inter', sans-serif; background: #030712; color: white; margin: 0; display: flex; overflow: hidden; }
        main { flex: 1; margin-left: var(--sidebar-width); position: relative; perspective: 3000px; height: 100vh; }
        #pan-wrapper { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transform-style: preserve-3d; }
        #scene { width: 2000px; height: 1600px; transform-style: preserve-3d; transform: rotateX(55deg) rotateZ(-30deg); }
        .grid-floor { width: 100%; height: 100%; border: 2px solid rgba(255,255,255,0.05); position: relative; transform-style: preserve-3d; background-image: linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 80px 80px; }
        
        .island { position: absolute; width: 220px; height: 150px; transform-style: preserve-3d; }
        
        /* MESA: translateZ a 40px */
        .table-surface { 
            position: absolute; width: 100%; height: 100%; 
            background: #1e293b; border: 2px solid var(--primary); 
            transform: translateZ(40px); border-radius: 15px; 
            display: flex; flex-direction: column; align-items: center; justify-content: center; 
            font-weight: 800; font-size: 0.8rem; cursor: pointer; transition: 0.3s;
        }
        .table-surface.can-book-all:hover { background: var(--primary); box-shadow: 0 0 30px var(--primary); }

        /* SILLAS: translateZ a 60px (MÁS ALTO QUE LA MESA PARA CLICKEAR) */
        .chair { 
            position: absolute; width: 44px; height: 44px; border-radius: 12px; 
            transform: translateZ(60px); border: 2px solid rgba(255,255,255,0.1); 
            cursor: pointer; transition: all 0.3s ease; z-index: 10;
        }
        .chair.free { background: var(--free); box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
        .chair.occupied { background: var(--occupied); opacity: 0.3; cursor: not-allowed; }
        .chair:hover.free { transform: translateZ(75px) scale(1.1); border-color: white; }

        .p-t1 { top: -60px; left: 30px; } .p-t2 { top: -60px; left: 140px; }
        .p-b1 { bottom: -60px; left: 30px; } .p-b2 { bottom: -60px; left: 140px; }
        
        #modal-confirm { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); display: none; align-items: center; justify-content: center; z-index: 10000; backdrop-filter: blur(10px); }
        .booking-card { background: #0f172a; padding: 3rem; border-radius: 40px; width: 550px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
    </style>
</head>
<body>
    <?php include '../includes/sidebar_user.php'; ?>
    <main id="viewport">
        <div style="position:absolute; top:40px; left:40px; z-index:100;">
            <h1 style="font-family:'Outfit'; font-size:2.5rem;"><?= htmlspecialchars($oficina['nombre'] ?? 'Oficina') ?></h1>
            <p style="color:#64748b;">Seleccioná una silla o la mesa completa si está disponible.</p>
        </div>

        <div id="pan-wrapper">
            <div id="scene">
                <div class="grid-floor">
                    <?php foreach ($islas as $isla): 
                        $stmtPuestos->execute([$isla['id']]);
                        $puestos = $stmtPuestos->fetchAll();
                        
                        $libres = 0;
                        foreach($puestos as $p) { if($p['estado'] == 'disponible') $libres++; }
                        $todaLibre = ($libres == count($puestos) && count($puestos) > 0);
                    ?>
                    <div class="island" style="top: <?= $isla['pos_y_isla'] ?>px; left: <?= $isla['pos_x_isla'] ?>px;">
                        <div class="table-surface <?= $todaLibre ? 'can-book-all' : '' ?>" 
                             onclick="bookIsland(<?= $isla['id'] ?>, '<?= htmlspecialchars($isla['nombre']) ?>', <?= $todaLibre ? 'true' : 'false' ?>)">
                             <?= htmlspecialchars($isla['nombre']) ?>
                             <?php if($todaLibre): ?>
                                <br><small style="color:var(--free); font-size:0.6rem;">RESERVAR TODA</small>
                             <?php endif; ?>
                        </div>

                        <?php 
                        $pos = ['p-t1', 'p-t2', 'p-b1', 'p-b2'];
                        foreach ($puestos as $i => $p): 
                        ?>
                        <div class="chair <?= $p['estado'] == 'disponible' ? 'free' : 'occupied' ?> <?= $pos[$i % 4] ?>" 
                             data-id="<?= $p['id'] ?>" data-name="<?= htmlspecialchars($p['nombre']) ?>"
                             onclick="bookIndividual(this)"></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div id="modal-confirm">
            <div class="booking-card">
                <h2 id="m-title" style="font-family:'Outfit'; margin:0 0 10px 0;">--</h2>
                <p id="m-desc" style="color:#94a3b8; margin-bottom:2rem;">--</p>
                
                <form action="procesar_reserva.php" method="POST">
                    <input type="hidden" name="puesto_id" id="form-puesto-id">
                    <input type="hidden" name="isla_id" id="form-isla-id">
                    <input type="hidden" name="tipo_reserva" id="form-tipo" value="individual">

                    <div style="margin-bottom:1.5rem;">
                        <label style="font-size:0.7rem; color:#64748b; text-transform:uppercase; font-weight:800;">Tiempo de estadía</label>
                        <select name="duracion" style="width:100%; padding:15px; background:#1e293b; border:1px solid #334155; color:white; border-radius:12px; margin-top:8px;">
                            <option value="1">1 Hora</option>
                            <option value="4">4 Horas</option>
                            <option value="8">Full Day</option>
                        </select>
                    </div>
                    <button type="submit" style="width:100%; padding:18px; background:var(--primary); border:none; color:white; border-radius:15px; font-weight:800; cursor:pointer; font-family:'Outfit';">CONFIRMAR RESERVA</button>
                    <button type="button" onclick="closeModal()" style="width:100%; background:none; border:none; color:#64748b; margin-top:1.5rem; cursor:pointer; font-weight:700;">Cancelar</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        function bookIndividual(el) {
            if(el.classList.contains('occupied')) return;
            event.stopPropagation();
            document.getElementById('form-tipo').value = 'individual';
            document.getElementById('form-puesto-id').value = el.dataset.id;
            document.getElementById('m-title').innerText = el.dataset.name;
            document.getElementById('m-desc').innerText = "Estás reservando este puesto individual.";
            document.getElementById('modal-confirm').style.display = 'flex';
        }

        function bookIsland(id, name, isFree) {
            if(!isFree) { alert("Esta isla tiene asientos ocupados. Reservá los lugares libres por separado."); return; }
            document.getElementById('form-tipo').value = 'isla_completa';
            document.getElementById('form-isla-id').value = id;
            document.getElementById('m-title').innerText = name + " (Completa)";
            document.getElementById('m-desc').innerText = "Vas a reservar todos los asientos de esta mesa para tu equipo.";
            document.getElementById('modal-confirm').style.display = 'flex';
        }

        function closeModal() { document.getElementById('modal-confirm').style.display = 'none'; }
    </script>
</body>
</html>