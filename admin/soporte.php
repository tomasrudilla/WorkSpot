<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Centro de Soporte</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --bg: #030712; 
            --sidebar: #0f172a; 
            --surface: rgba(30, 41, 59, 0.45); 
            --primary: #6366f1; 
            --text-main: #f8fafc; 
            --text-muted: #94a3b8; 
            --border: rgba(255, 255, 255, 0.08); 
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        main { flex: 1; padding: 2.5rem 4rem; overflow-y: auto; }

        /* --- Stats de Soporte --- */
        .support-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .stat-box {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            text-align: center;
        }
        .stat-box h3 { font-family: 'Outfit', sans-serif; font-size: 1.8rem; margin: 5px 0; }
        .stat-box p { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; }

        /* --- Toolbar --- */
        .support-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .support-header h1 { font-family: 'Outfit', sans-serif; font-size: 2.2rem; margin: 0; }

        .search-container {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 10px 15px 10px 40px;
            width: 350px;
        }
        .search-container i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .search-container input { background: transparent; border: none; color: white; outline: none; width: 100%; }

        /* --- Tabla de Tickets --- */
        .ticket-list { 
            background: var(--surface); 
            border-radius: 24px; 
            border: 1px solid var(--border); 
            overflow: hidden; 
            backdrop-filter: blur(10px);
        }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1.2rem 2rem; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); }
        td { padding: 1.2rem 2rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:hover { background: rgba(255,255,255,0.02); }

        /* Prioridades */
        .prio-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }
        .prio-critical { background: rgba(239, 68, 68, 0.15); color: var(--danger); }
        .prio-high { background: rgba(245, 158, 11, 0.15); color: var(--warning); }
        .prio-medium { background: rgba(14, 165, 233, 0.15); color: var(--info); }
        .prio-low { background: rgba(16, 185, 129, 0.15); color: var(--success); }

        /* Estados */
        .status-tag { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; }
        .status-tag::before { content: ''; width: 8px; height: 8px; border-radius: 50%; }
        .st-open::before { background: var(--danger); box-shadow: 0 0 8px var(--danger); }
        .st-progress::before { background: var(--warning); box-shadow: 0 0 8px var(--warning); }
        .st-closed::before { background: var(--success); }

        /* Avatar de Cliente */
        .client-info { display: flex; align-items: center; gap: 10px; }
        .avatar-sm { width: 32px; height: 32px; border-radius: 8px; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: white; }

        .action-group { display: flex; gap: 8px; }
        .btn-icon { background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: var(--text-muted); padding: 8px; border-radius: 8px; cursor: pointer; }
        .btn-icon:hover { color: var(--primary); border-color: var(--primary); }
    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="support-header">
            <div>
                <h1>Centro de Soporte</h1>
                <p style="color: var(--text-muted);">Gestiona las incidencias y consultas de la comunidad WorkSpot.</p>
            </div>
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar ticket por ID o usuario...">
            </div>
        </div>

        <div class="support-stats">
            <div class="stat-box">
                <p>Abiertos</p>
                <h3 style="color: var(--danger);">14</h3>
            </div>
            <div class="stat-box">
                <p>En Progreso</p>
                <h3 style="color: var(--warning);">08</h3>
            </div>
            <div class="stat-box">
                <p>T. Respuesta</p>
                <h3>12m</h3>
            </div>
            <div class="stat-box">
                <p>Satisfacción</p>
                <h3 style="color: var(--success);">98%</h3>
            </div>
        </div>

        <div class="ticket-list">
            <table>
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Cliente</th>
                        <th>Asunto</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 700;">#TK-4402</td>
                        <td>
                            <div class="client-info">
                                <div class="avatar-sm" style="background: #6366f1;">LM</div>
                                <span>Lionel Messi</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Falla conexión WiFi - Sala VIP</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Reportado hace 5 min</div>
                        </td>
                        <td><span class="prio-badge prio-critical">Crítica</span></td>
                        <td><div class="status-tag st-open">Abierto</div></td>
                        <td>
                            <div class="action-group">
                                <button class="btn-icon" title="Responder"><i class="fas fa-reply"></i></button>
                                <button class="btn-icon" title="Cerrar"><i class="fas fa-check"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight: 700;">#TK-4401</td>
                        <td>
                            <div class="client-info">
                                <div class="avatar-sm" style="background: #f59e0b;">EM</div>
                                <span>Elon Musk</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Error en factura de Diciembre</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Reportado hace 2 horas</div>
                        </td>
                        <td><span class="prio-badge prio-high">Alta</span></td>
                        <td><div class="status-tag st-progress">En proceso</div></td>
                        <td>
                            <div class="action-group">
                                <button class="btn-icon"><i class="fas fa-reply"></i></button>
                                <button class="btn-icon"><i class="fas fa-check"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight: 700;">#TK-4399</td>
                        <td>
                            <div class="client-info">
                                <div class="avatar-sm" style="background: #0ea5e9;">LS</div>
                                <span>Luis Scaloni</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Solicitud de proyector extra</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Reportado hoy, 10:30 AM</div>
                        </td>
                        <td><span class="prio-badge prio-medium">Media</span></td>
                        <td><div class="status-tag st-closed">Resuelto</div></td>
                        <td>
                            <div class="action-group">
                                <button class="btn-icon"><i class="fas fa-eye"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight: 700;">#TK-4395</td>
                        <td>
                            <div class="client-info">
                                <div class="avatar-sm" style="background: #10b981;">AR</div>
                                <span>Antonela R.</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Consulta sobre plan Corporate</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Reportado ayer</div>
                        </td>
                        <td><span class="prio-badge prio-low">Baja</span></td>
                        <td><div class="status-tag st-closed">Resuelto</div></td>
                        <td>
                            <div class="action-group">
                                <button class="btn-icon"><i class="fas fa-eye"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>