<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Control de Reservas</title>
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

        /* --- Encabezado --- */
        .header-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .header-area h1 { font-family: 'Outfit', sans-serif; font-size: 2.2rem; letter-spacing: -1px; }

        /* --- Barra de Herramientas (Filtros y Búsqueda) --- */
        .table-tools {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--surface);
            padding: 1rem 1.5rem;
            border-radius: 18px;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .search-wrapper {
            position: relative;
            width: 350px;
        }

        .search-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            outline: none;
        }

        .filter-tabs { display: flex; gap: 8px; }
        .tab {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            color: var(--text-muted);
            transition: 0.3s;
        }
        .tab.active { background: rgba(99, 102, 241, 0.1); color: var(--primary); border-color: var(--primary); }
        .tab:hover:not(.active) { background: rgba(255,255,255,0.05); color: var(--text-main); }

        /* --- Tabla Estilizada --- */
        .table-container {
            background: var(--surface);
            border-radius: 24px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        table { width: 100%; border-collapse: collapse; }
        
        thead { background: rgba(0,0,0,0.2); }
        th { 
            text-align: left; 
            padding: 1.2rem 1.5rem; 
            color: var(--text-muted); 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            font-weight: 700;
        }

        tbody tr { border-bottom: 1px solid var(--border); transition: 0.2s; }
        tbody tr:hover { background: rgba(255,255,255,0.02); }
        tbody tr:last-child { border: none; }

        td { padding: 1.2rem 1.5rem; font-size: 0.95rem; vertical-align: middle; }

        /* Celda de Cliente */
        .client-cell { display: flex; align-items: center; gap: 12px; }
        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
        }

        /* Badges de Estado */
        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        .paid { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .cancelled { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        /* Botones de Acción */
        .action-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--text-muted);
            width: 35px;
            height: 35px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .action-btn:hover { color: var(--primary); border-color: var(--primary); background: rgba(99, 102, 241, 0.1); }

    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="header-area">
            <div>
                <h1>Control de Reservas</h1>
                <p style="color: var(--text-muted);">Listado total de transacciones y estados de activos.</p>
            </div>
            <button style="background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer;">
                <i class="fas fa-file-export"></i> Exportar Reporte
            </button>
        </div>

        <div class="table-tools">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar por cliente, ID o espacio...">
            </div>
            <div class="filter-tabs">
                <div class="tab active">Todas</div>
                <div class="tab">Pagadas</div>
                <div class="tab">Pendientes</div>
                <div class="tab">Canceladas</div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Cliente</th>
                        <th>Espacio / Activo</th>
                        <th>Fecha & Hora</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 700; color: var(--primary);">#WS-1024</td>
                        <td>
                            <div class="client-cell">
                                <div class="avatar-circle" style="background: #ef4444;">LM</div>
                                <div>
                                    <div style="font-weight: 600;">Lionel Messi</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">l.messi@inter.miami</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Sala VIP "Marte"</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Piso 12 - Sector A</div>
                        </td>
                        <td>
                            <div>24 Dic, 2025</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">14:00 - 18:00 (4h)</div>
                        </td>
                        <td style="font-weight: 700;">$4,500.00</td>
                        <td><span class="badge paid">Completado</span></td>
                        <td>
                            <button class="action-btn" title="Ver detalle"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Editar"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight: 700; color: var(--primary);">#WS-1025</td>
                        <td>
                            <div class="client-cell">
                                <div class="avatar-circle" style="background: #38bdf8;">MZ</div>
                                <div>
                                    <div style="font-weight: 600;">Mark Zuckerberg</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">zuck@meta.com</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Meta Lounge</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Piso 08 - Box 02</div>
                        </td>
                        <td>
                            <div>25 Dic, 2025</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">09:00 - 12:00 (3h)</div>
                        </td>
                        <td style="font-weight: 700;">$1,200.00</td>
                        <td><span class="badge pending">Pendiente</span></td>
                        <td>
                            <button class="action-btn"><i class="fas fa-eye"></i></button>
                            <button class="action-btn"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight: 700; color: var(--primary);">#WS-1026</td>
                        <td>
                            <div class="client-cell">
                                <div class="avatar-circle" style="background: #f59e0b;">EM</div>
                                <div>
                                    <div style="font-weight: 600;">Elon Musk</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">elon@spacex.com</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Oficina "Starship"</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Piso 15 - Suite</div>
                        </td>
                        <td>
                            <div>26 Dic, 2025</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">08:00 - 20:00 (Full Day)</div>
                        </td>
                        <td style="font-weight: 700;">$8,000.00</td>
                        <td><span class="badge cancelled">Cancelado</span></td>
                        <td>
                            <button class="action-btn"><i class="fas fa-eye"></i></button>
                            <button class="action-btn"><i class="fas fa-undo"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight: 700; color: var(--primary);">#WS-1027</td>
                        <td>
                            <div class="client-cell">
                                <div class="avatar-circle" style="background: #10b981;">LS</div>
                                <div>
                                    <div style="font-weight: 600;">Luis Scaloni</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">l.scaloni@afa.ar</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Sala "La Scaloneta"</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Sector Estrategia</div>
                        </td>
                        <td>
                            <div>27 Dic, 2025</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">10:00 - 14:00 (4h)</div>
                        </td>
                        <td style="font-weight: 700;">$2,800.00</td>
                        <td><span class="badge paid">Completado</span></td>
                        <td>
                            <button class="action-btn"><i class="fas fa-eye"></i></button>
                            <button class="action-btn"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>