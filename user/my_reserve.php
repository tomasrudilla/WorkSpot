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
            --primary: #6366f1;
            --secondary: #0ea5e9;
            --bg: #030712;
            --surface: rgba(17, 24, 39, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
            --glow: rgba(99, 102, 241, 0.3);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --accent: #00f2ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Background Glow (Añadido para igualar profundidad) */
        .aurora {
            position: fixed; top: -100px; right: -100px;
            width: 50vw; height: 50vw; background: var(--gradient);
            filter: blur(150px); opacity: 0.1; z-index: -1;
        }

        main {
            flex: 1;
            margin-left: 290px; /* Asegurate que este ancho coincida con el width real de tu sidebar */
            padding: 4rem; /* Unificado con la otra página */
        }

        /* --- Header Section (Unificado) --- */
        .header-section {
            margin-bottom: 3.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .header-section h1 {
            font-family: 'Outfit';
            font-size: 3rem; /* Subido de 2.8rem a 3rem para igualar */
            letter-spacing: -1.5px;
            margin-bottom: 0.5rem;
        }

        .btn-new {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 18px;
            font-family: 'Outfit';
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px var(--glow);
            transition: 0.3s;
        }

        .btn-new:hover { transform: translateY(-3px); opacity: 0.9; }

        /* --- Stats Row --- */
        .reserve-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            padding: 1.8rem; border-radius: 28px; backdrop-filter: blur(20px);
        }
        .stat-card p { font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; }
        .stat-card h2 { font-family: 'Outfit'; font-size: 2.2rem; margin: 0; }
        .stat-card h2 span { font-size: 1rem; color: var(--accent); opacity: 0.8; }

        /* --- Tabs (Unificado con estilo de botones de filtro) --- */
        .filter-tabs { display: flex; gap: 12px; margin-bottom: 3rem; }
        .tab-btn {
            padding: 10px 22px;
            border-radius: 50px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .tab-btn.active { 
            border-color: var(--primary); 
            color: white; 
            background: rgba(99, 102, 241, 0.1);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.1);
        }

        /* --- Grid de Cards --- */
        .reserve-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); /* Igualado a 350px */
            gap: 2.5rem;
        }

        .reserve-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 35px; /* Unificado a 35px */
            padding: 2.2rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .reserve-card:hover {
            transform: translateY(-12px);
            border-color: var(--primary);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; }
        
        .icon-circle {
            width: 54px; height: 54px; border-radius: 18px;
            background: rgba(255,255,255,0.03);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: var(--primary);
            border: 1px solid var(--border);
        }

        .status-badge {
            padding: 6px 14px; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; border: 1px solid transparent;
        }
        .status-badge.confirmed { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: var(--success); }
        .status-badge.pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); border-color: var(--warning); }
        .status-badge.completed { background: rgba(255,255,255,0.05); color: var(--text-muted); border-color: var(--border); }

        .card-content h3 { font-family: 'Outfit'; font-size: 1.6rem; margin: 0 0 8px 0; }
        .card-content p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; }

        .time-box {
            background: rgba(255,255,255,0.02);
            border-radius: 20px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
        }
        .time-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 0.9rem; }
        .time-row:last-child { margin-bottom: 0; }
        .time-row i { color: var(--secondary); width: 15px; text-align: center; }

        .card-actions {
            display: flex; gap: 10px;
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }

        .action-btn {
            flex: 1; padding: 12px; border-radius: 15px; border: 1px solid var(--border);
            background: rgba(255,255,255,0.03); color: var(--text-main); cursor: pointer;
            font-weight: 700; font-size: 0.85rem; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-family: 'Outfit';
        }

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
            <button class="btn-new">
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

        <div class="reserve-grid">
            <div class="reserve-card">
                <div class="card-top">
                    <div class="icon-circle" style="color: var(--accent); border-color: rgba(0,242,255,0.2);">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <span class="status-badge confirmed">Confirmada</span>
                </div>
                <div class="card-content">
                    <h3>Sala Silicon Valley</h3>
                    <p>Piso 2 • Sala de Juntas</p>
                    <div class="time-box">
                        <div class="time-row"><i class="fas fa-calendar-day"></i> <b>Hoy, 25 Dic 2025</b></div>
                        <div class="time-row"><i class="fas fa-clock"></i> <span>14:00 - 18:00 (4h)</span></div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="action-btn"><i class="fas fa-map-marked-alt"></i> Mapa</button>
                    <button class="action-btn danger"><i class="fas fa-times"></i> Cancelar</button>
                </div>
            </div>

            <div class="reserve-card">
                <div class="card-top">
                    <div class="icon-circle">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <span class="status-badge pending">Pendiente</span>
                </div>
                <div class="card-content">
                    <h3>Desk Individual 04</h3>
                    <p>Piso 4 • Sector Coworking</p>
                    <div class="time-box">
                        <div class="time-row"><i class="fas fa-calendar-day"></i> <b>Mañana, 26 Dic 2025</b></div>
                        <div class="time-row"><i class="fas fa-clock"></i> <span>09:00 - 13:00 (4h)</span></div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="action-btn" style="color: var(--warning); border-color: var(--warning);"><i class="fas fa-credit-card"></i> Pagar</button>
                    <button class="action-btn danger"><i class="fas fa-times"></i> Cancelar</button>
                </div>
            </div>

            <div class="reserve-card" style="opacity: 0.8;">
                <div class="card-top">
                    <div class="icon-circle">
                        <i class="fas fa-couch"></i>
                    </div>
                    <span class="status-badge completed">Completada</span>
                </div>
                <div class="card-content">
                    <h3>Lounge Creativo</h3>
                    <p>Piso 1 • Área Relax</p>
                    <div class="time-box">
                        <div class="time-row"><i class="fas fa-calendar-day"></i> <b>23 Dic 2025</b></div>
                        <div class="time-row"><i class="fas fa-clock"></i> <span>10:00 - 12:00 (2h)</span></div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="action-btn"><i class="fas fa-file-invoice"></i> Ticket</button>
                    <button class="action-btn"><i class="fas fa-redo"></i> Repetir</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('.tab-btn.active').classList.remove('active');
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>