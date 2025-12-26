<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Support Command Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --bg: #030712; 
            --surface: rgba(30, 41, 59, 0.4); 
            --primary: #6366f1; 
            --accent: #00f2ff;
            --text-main: #f8fafc; 
            --text-muted: #94a3b8; 
            --border: rgba(255, 255, 255, 0.08); 
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        main { flex: 1; padding: 2rem 4rem; overflow-y: auto; }

        /* --- Header & Search --- */
        .support-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .support-header h1 { font-family: 'Outfit'; font-size: 2.5rem; margin: 0; letter-spacing: -1.5px; }

        .search-bar {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 12px 20px 12px 45px;
            width: 380px;
            backdrop-filter: blur(10px);
        }
        .search-bar i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .search-bar input { background: transparent; border: none; color: white; outline: none; width: 100%; font-size: 0.95rem; }

        /* --- Stats Widgets --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 1.5rem;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: var(--primary); opacity: 0.3;
        }
        .stat-card i { font-size: 1.2rem; color: var(--primary); margin-bottom: 10px; display: block; }
        .stat-card h3 { font-family: 'Outfit'; font-size: 1.8rem; margin: 5px 0; }
        .stat-card p { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 1px; margin: 0; }

        /* --- Filter Tabs --- */
        .filter-nav { display: flex; gap: 10px; margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem; }
        .filter-btn {
            background: none; border: none; color: var(--text-muted); padding: 8px 16px; border-radius: 10px; cursor: pointer;
            font-weight: 600; font-size: 0.9rem; transition: 0.3s;
        }
        .filter-btn.active { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .filter-btn:hover:not(.active) { color: white; background: rgba(255,255,255,0.05); }

        /* --- Ticket "Cards" (En lugar de tabla) --- */
        .tickets-container { display: flex; flex-direction: column; gap: 1rem; }
        
        .ticket-row {
            display: grid;
            grid-template-columns: 100px 200px 1fr 150px 150px 120px;
            align-items: center;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 1.5rem 2rem;
            border-radius: 24px;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        .ticket-row:hover {
            transform: scale(1.01);
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .tk-id { font-weight: 800; color: var(--primary); font-size: 0.9rem; }
        
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-ava { width: 35px; height: 35px; border-radius: 10px; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; color: white; }

        .subject-box h4 { margin: 0; font-size: 1rem; font-weight: 600; color: white; }
        .subject-box span { font-size: 0.75rem; color: var(--text-muted); }

        /* Prioridades Neón */
        .prio {
            font-size: 0.65rem; font-weight: 900; text-transform: uppercase; padding: 5px 12px; border-radius: 50px; width: fit-content;
        }
        .prio-crit { color: var(--danger); background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger); box-shadow: 0 0 10px rgba(239, 68, 68, 0.2); }
        .prio-high { color: var(--warning); background: rgba(245, 158, 11, 0.1); border: 1px solid var(--warning); }
        .prio-low { color: var(--success); background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); }

        /* Estados */
        .status-pill { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 0.8rem; }
        .status-pill::before { content: ''; width: 8px; height: 8px; border-radius: 50%; }
        .status-open::before { background: var(--danger); animation: pulse-red 2s infinite; }
        
        @keyframes pulse-red {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.4; }
            100% { transform: scale(1); opacity: 1; }
        }

        .actions { display: flex; gap: 8px; justify-content: flex-end; }
        .action-btn { background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: var(--text-muted); padding: 10px; border-radius: 12px; transition: 0.3s; cursor: pointer; }
        .action-btn:hover { color: var(--primary); border-color: var(--primary); background: rgba(99, 102, 241, 0.1); }

    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="support-header">
            <div>
                <h1>Support Center</h1>
                <p style="color: var(--text-muted); margin-top: 5px;">Panel de resolución de incidencias en tiempo real.</p>
            </div>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar ticket, usuario o error...">
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card" style="--primary: var(--danger)">
                <i class="fas fa-envelope-open"></i>
                <p>Abiertos</p>
                <h3>14</h3>
            </div>
            <div class="stat-card" style="--primary: var(--warning)">
                <i class="fas fa-spinner"></i>
                <p>En Progreso</p>
                <h3>08</h3>
            </div>
            <div class="stat-card">
                <i class="fas fa-bolt"></i>
                <p>Respuesta Media</p>
                <h3>12m</h3>
            </div>
            <div class="stat-card" style="--primary: var(--success)">
                <i class="fas fa-smile"></i>
                <p>Satisfacción</p>
                <h3>98%</h3>
            </div>
        </div>

        <div class="filter-nav">
            <button class="filter-btn active">Todos los Tickets</button>
            <button class="filter-btn">Mis Asignados</button>
            <button class="filter-btn">Urgentes</button>
            <button class="filter-btn">Resueltos</button>
        </div>

        <div class="tickets-container">
            
            <div class="ticket-row">
                <div class="tk-id">#TK-4402</div>
                <div class="user-info">
                    <div class="user-ava">LM</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Lionel Messi</div>
                </div>
                <div class="subject-box">
                    <h4>Falla conexión WiFi - Sala VIP</h4>
                    <span>Reportado hace 5 min • Sector A</span>
                </div>
                <div><span class="prio prio-crit">Crítica</span></div>
                <div class="status-pill status-open" style="color: var(--danger)">Abierto</div>
                <div class="actions">
                    <button class="action-btn"><i class="fas fa-reply"></i></button>
                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>

            <div class="ticket-row">
                <div class="tk-id">#TK-4401</div>
                <div class="user-info">
                    <div class="user-ava" style="background: #f59e0b;">EM</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Elon Musk</div>
                </div>
                <div class="subject-box">
                    <h4>Error en factura de Diciembre</h4>
                    <span>Reportado hace 2 horas • Facturación</span>
                </div>
                <div><span class="prio prio-high">Alta</span></div>
                <div class="status-pill" style="color: var(--warning);"><i class="fas fa-circle" style="font-size: 0.5rem;"></i> En proceso</div>
                <div class="actions">
                    <button class="action-btn"><i class="fas fa-reply"></i></button>
                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>

            <div class="ticket-row">
                <div class="tk-id">#TK-4399</div>
                <div class="user-info">
                    <div class="user-ava" style="background: #0ea5e9;">LS</div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Luis Scaloni</div>
                </div>
                <div class="subject-box">
                    <h4>Solicitud de proyector extra</h4>
                    <span>Hoy, 10:30 AM • Sala Estrategia</span>
                </div>
                <div><span class="prio" style="border: 1px solid var(--border); color: var(--text-muted);">Media</span></div>
                <div class="status-pill" style="color: var(--success);"><i class="fas fa-check-circle"></i> Resuelto</div>
                <div class="actions">
                    <button class="action-btn"><i class="fas fa-eye"></i></button>
                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Efecto visual simple para los botones de filtro
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    </script>
</body>
</html>