<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot | Reservar Espacio</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary:#6366f1; --bg:#030712; --surface:rgba(15,23,42,0.7); --border:rgba(255,255,255,0.08); --text-muted:#94a3b8; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:white; margin:0; display:flex; min-height:100vh; overflow-x:hidden; }
        main { flex:1; margin-left:290px; padding:3.5rem 5rem; min-height:100vh; }
        .header-section { margin-bottom: 3.5rem; }
        .header-section h1 { font-family:'Outfit'; font-size:3rem; margin:0; letter-spacing:-1.5px; }
        .space-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2.5rem; }
        .space-card { background: var(--surface); border: 1px solid var(--border); border-radius: 35px; overflow: hidden; transition: 0.4s; cursor: pointer; backdrop-filter: blur(20px); }
        .space-card:hover { transform: translateY(-12px); border-color: var(--primary); box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        .card-img { height: 220px; background-size: cover; background-position: center; position: relative; }
        .card-body { padding: 2rem; }
        .btn-reserve { width: 100%; padding: 16px; background: var(--primary); color: white; border: none; border-radius: 18px; font-weight: 700; cursor: pointer; transition: 0.3s; }
    </style>
</head>
<body>
    <?php include '../includes/sidebar_user.php'; ?>
    <main>
        <div class="header-section">
            <h1>Encontrá tu lugar 🚀</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Explorá los sectores disponibles para hoy.</p>
        </div>

        <div class="space-grid">
            <div class="space-card" onclick="location.href='map_reserve.php?sala=Isla Alpha-01'">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600');"></div>
                <div class="card-body">
                    <span style="color:#10b981; font-size:0.75rem; font-weight:900; text-transform:uppercase;">● 12 Libres</span>
                    <h3 style="font-family:'Outfit'; font-size:1.5rem; margin:10px 0;">Isla Alpha-01</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:20px;">Zona de alta productividad con monitores duales.</p>
                    <button class="btn-reserve">Ver Plano 3D</button>
                </div>
            </div>

            <div class="space-card" onclick="location.href='map_reserve.php?sala=Sala Scrum'">
                <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=600');"></div>
                <div class="card-body">
                    <span style="color:#10b981; font-size:0.75rem; font-weight:900; text-transform:uppercase;">● Disponible</span>
                    <h3 style="font-family:'Outfit'; font-size:1.5rem; margin:10px 0;">Sala Scrum Gamma</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:20px;">Espacio privado para equipos de hasta 6 personas.</p>
                    <button class="btn-reserve">Ver Plano 3D</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>