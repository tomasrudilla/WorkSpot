<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Gestión de Miembros</title>
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
            --info: #0ea5e9;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        main { flex: 1; padding: 2.5rem 4rem; overflow-y: auto; }

        /* --- Métricas Rápidas --- */
        .user-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .mini-card {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .mini-card i {
            width: 45px; height: 45px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }

        /* --- Toolbar --- */
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header-flex h1 { font-family: 'Outfit', sans-serif; font-size: 2.2rem; margin: 0; }

        .search-box {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 10px 15px 10px 40px;
            width: 300px;
        }
        .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .search-box input { background: transparent; border: none; color: white; outline: none; width: 100%; }

        /* --- Tabla de Usuarios --- */
        .user-list { 
            background: var(--surface); 
            border-radius: 24px; 
            border: 1px solid var(--border); 
            overflow: hidden; 
            backdrop-filter: blur(10px);
        }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1.2rem 2rem; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); }
        td { padding: 1rem 2rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:hover { background: rgba(255,255,255,0.02); }

        /* Estilo de Perfil */
        .profile-info { display: flex; align-items: center; gap: 12px; }
        .avatar {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: white;
            position: relative;
        }
        .status-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            border: 2px solid var(--bg);
            position: absolute; bottom: -2px; right: -2px;
        }
        .dot-online { background: var(--success); }
        .dot-offline { background: var(--text-muted); }

        /* Badges de Planes */
        .plan-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .plan-enterprise { background: rgba(99, 102, 241, 0.15); color: #818cf8; }
        .plan-pro { background: rgba(14, 165, 233, 0.15); color: #38bdf8; }
        .plan-starter { background: rgba(148, 163, 184, 0.15); color: #94a3b8; }

        .btn-action {
            background: none; border: 1px solid var(--border);
            color: var(--text-muted); padding: 8px; border-radius: 8px;
            cursor: pointer; transition: 0.3s;
        }
        .btn-action:hover { color: var(--primary); border-color: var(--primary); background: rgba(99,102,241,0.05); }

        .btn-new { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="header-flex">
            <h1>Gestión de Miembros</h1>
            <div style="display: flex; gap: 1rem;">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar miembro...">
                </div>
                <button class="btn-new"><i class="fas fa-user-plus"></i> Invitar</button>
            </div>
        </div>

        <div class="user-stats">
            <div class="mini-card">
                <i class="fas fa-users"></i>
                <div>
                    <h3 style="margin:0;">1,248</h3>
                    <p style="margin:0; font-size:0.8rem; color:var(--text-muted);">Total Miembros</p>
                </div>
            </div>
            <div class="mini-card">
                <i class="fas fa-bolt"></i>
                <div>
                    <h3 style="margin:0;">84</h3>
                    <p style="margin:0; font-size:0.8rem; color:var(--text-muted);">Activos Ahora</p>
                </div>
            </div>
            <div class="mini-card">
                <i class="fas fa-user-plus"></i>
                <div>
                    <h3 style="margin:0;">+12</h3>
                    <p style="margin:0; font-size:0.8rem; color:var(--text-muted);">Nuevos (Este mes)</p>
                </div>
            </div>
        </div>

        <div class="user-list">
            <table>
                <thead>
                    <tr>
                        <th>Miembro</th>
                        <th>Email</th>
                        <th>Plan Activo</th>
                        <th>Última Conexión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="profile-info">
                                <div class="avatar" style="background: var(--primary);">
                                    TG <span class="status-dot dot-online"></span>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">Tomás González</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #001 (Admin)</div>
                                </div>
                            </div>
                        </td>
                        <td>tomas@workspot.com</td>
                        <td><span class="plan-badge plan-enterprise">Enterprise</span></td>
                        <td>Hace 2 minutos</td>
                        <td>
                            <button class="btn-action"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="profile-info">
                                <div class="avatar" style="background: #ef4444;">
                                    LM <span class="status-dot dot-online"></span>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">Lionel Messi</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #101</div>
                                </div>
                            </div>
                        </td>
                        <td>l.messi@inter.miami</td>
                        <td><span class="plan-badge plan-enterprise">Enterprise</span></td>
                        <td>En línea</td>
                        <td>
                            <button class="btn-action"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="profile-info">
                                <div class="avatar" style="background: #0ea5e9;">
                                    LS <span class="status-dot dot-offline"></span>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">Luis Scaloni</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #102</div>
                                </div>
                            </div>
                        </td>
                        <td>l.scaloni@afa.ar</td>
                        <td><span class="plan-badge plan-pro">Pro Individual</span></td>
                        <td>Ayer, 18:45</td>
                        <td>
                            <button class="btn-action"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="profile-info">
                                <div class="avatar" style="background: #f59e0b;">
                                    AM <span class="status-dot dot-online"></span>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">Antonela Roccuzzo</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #103</div>
                                </div>
                            </div>
                        </td>
                        <td>anto.roc@icloud.com</td>
                        <td><span class="plan-badge plan-pro">Pro Individual</span></td>
                        <td>En línea</td>
                        <td>
                            <button class="btn-action"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="profile-info">
                                <div class="avatar" style="background: #94a3b8;">
                                    SM <span class="status-dot dot-offline"></span>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">Santi Maratea</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">ID: #104</div>
                                </div>
                            </div>
                        </td>
                        <td>santi@colecta.ar</td>
                        <td><span class="plan-badge plan-starter">Starter</span></td>
                        <td>Hace 3 días</td>
                        <td>
                            <button class="btn-action"><i class="fas fa-ellipsis-v"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>