<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot | Mis Reservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --accent: #00f2ff;
            --bg: #030712;
            --surface: rgba(15, 23, 42, 0.6);
            --border: rgba(255, 255, 255, 0.08);
            --text-muted: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: white; margin: 0; display: flex; min-height: 100vh; }
        
        /* Ajuste para el Sidebar Premium */
        main { 
            flex: 1; 
            margin-left: 290px; 
            padding: 2.5rem 4rem; 
            min-height: 100vh;
            background-image: radial-gradient(circle at top right, rgba(99, 102, 241, 0.05), transparent);
        }

        .header-flex { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; }
        .header-flex h1 { font-family: 'Outfit'; font-size: 2.8rem; margin: 0; letter-spacing: -1.5px; }

        /* --- STATS ROW --- */
        .reserve-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            padding: 1.5rem; border-radius: 25px; backdrop-filter: blur(10px);
        }
        .stat-card p { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin: 0 0 10px 0; }
        .stat-card h2 { font-family: 'Outfit'; font-size: 2.2rem; margin: 0; }
        .stat-card h2 span { font-size: 1rem; color: var(--accent); }

        /* --- FILTROS --- */
        .filter-tabs { display: flex; gap: 10px; margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem; }
        .tab-btn {
            background: none; border: none; color: var(--text-muted); padding: 10px 20px; border-radius: 12px;
            font-weight: 700; cursor: pointer; transition: 0.3s;
        }
        .tab-btn.active { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .tab-btn:hover:not(.active) { color: white; background: rgba(255,255,255,0.03); }

        /* --- LISTA DE RESERVAS --- */
        .reserve-container { display: flex; flex-direction: column; gap: 1rem; }
        
        .reserve-item {
            display: grid;
            grid-template-columns: 80px 2fr 1.5fr 1fr 120px;
            align-items: center;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 1.5rem 2rem;
            border-radius: 28px;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .reserve-item:hover {
            transform: scale(1.01);
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .icon-box {
            width: 50px; height: 50px; border-radius: 15px;
            background: rgba(255,255,255,0.03);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: var(--primary);
        }

        .space-info h3 { margin: 0; font-family: 'Outfit'; font-size: 1.2rem; color: white; }
        .space-info span { font-size: 0.8rem; color: var(--text-muted); }

        .time-info b { display: block; font-size: 0.95rem; color: white; }
        .time-info span { font-size: 0.8rem; color: var(--text-muted); }

        /* Badges Neón */
        .status-badge {
            padding: 6px 14px; border-radius: 50px; font-size: 0.7rem; font-weight: 900;
            text-transform: uppercase; letter-spacing: 0.5px; width: fit-content;
        }
        .confirmed { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid var(--success); }
        .pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid var(--warning); }
        .completed { background: rgba(255, 255, 255, 0.05); color: var(--text-muted); border: 1px solid var(--border); }

        .actions { display: flex; gap: 10px; justify-content: flex-end; }
        .action-circle {
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(255,255,255,0.05); border: 1px solid var(--border);
            color: var(--text-muted); cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; justify-content: center;
        }
        .action-circle:hover { color: var(--primary); border-color: var(--primary); background: rgba(99, 102, 241, 0.1); }
        .cancel-btn:hover { color: var(--danger); border-color: var(--danger); background: rgba(239, 68, 68, 0.1); }

    </style>
</head>
<body>

    <?php include '../includes/sidebar_user.php'; ?>

    <main>
        <div class="header-flex">
            <div>
                <p style="color: var(--primary); font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px;">Historial y Próximos</p>
                <h1>Mis Reservas</h1>
            </div>
            <button style="background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 15px; font-weight: 700; cursor: pointer;">
                <i class="fas fa-plus"></i> Nueva Reserva
            </button>
        </div>

        <div class="reserve-stats">
            <div class="stat-card">
                <p>Uso este Mes</p>
                <h2>42 <span>horas</span></h2>
            </div>
            <div class="stat-card">
                <p>Reservas Activas</p>
                <h2 style="color: var(--accent);">03</h2>
            </div>
            <div class="stat-card">
                <p>Créditos Gastados</p>
                <h2>12.4k <span>pts</span></h2>
            </div>
        </div>

        <div class="filter-tabs">
            <button class="tab-btn active">Próximas</button>
            <button class="tab-btn">Pasadas</button>
            <button class="tab-btn">Canceladas</button>
        </div>

        <div class="reserve-container">
            
            <div class="reserve-item">
                <div class="icon-box" style="background: rgba(0, 242, 255, 0.1); color: var(--accent);">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="space-info">
                    <h3>Sala Silicon Valley</h3>
                    <span>Piso 2 • Sala de Juntas</span>
                </div>
                <div class="time-info">
                    <b>Hoy, 25 Dic 2025</b>
                    <span>14:00 - 18:00 (4h)</span>
                </div>
                <div><span class="status-badge confirmed">Confirmada</span></div>
                <div class="actions">
                    <button class="action-circle" title="Ver Mapa"><i class="fas fa-map-location-dot"></i></button>
                    <button class="action-circle cancel-btn" title="Cancelar"><i class="fas fa-xmark"></i></button>
                </div>
            </div>

            <div class="reserve-item">
                <div class="icon-box">
                    <i class="fas fa-desktop"></i>
                </div>
                <div class="space-info">
                    <h3>Desk Individual 04</h3>
                    <span>Piso 4 • Sector Coworking</span>
                </div>
                <div class="time-info">
                    <b>Mañana, 26 Dic 2025</b>
                    <span>09:00 - 13:00 (4h)</span>
                </div>
                <div><span class="status-badge pending">Pendiente de Pago</span></div>
                <div class="actions">
                    <button class="action-circle" style="color: var(--warning); border-color: var(--warning);"><i class="fas fa-credit-card"></i></button>
                    <button class="action-circle cancel-btn"><i class="fas fa-xmark"></i></button>
                </div>
            </div>

            <div class="reserve-item" style="opacity: 0.7;">
                <div class="icon-box">
                    <i class="fas fa-couch"></i>
                </div>
                <div class="space-info">
                    <h3>Lounge Creativo</h3>
                    <span>Piso 1 • Área Relax</span>
                </div>
                <div class="time-info">
                    <b>23 Dic 2025</b>
                    <span>10:00 - 12:00 (2h)</span>
                </div>
                <div><span class="status-badge completed">Completada</span></div>
                <div class="actions">
                    <button class="action-circle"><i class="fas fa-file-invoice"></i></button>
                    <button class="action-circle"><i class="fas fa-rotate-right"></i></button>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Lógica simple de navegación de pestañas
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });
    </script>
</body>
</html>