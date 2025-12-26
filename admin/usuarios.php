<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Member Directory</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --bg: #030712; 
            --surface: rgba(30, 41, 59, 0.4); 
            --primary: #6366f1; 
            --text-main: #f8fafc; 
            --text-muted: #94a3b8; 
            --border: rgba(255, 255, 255, 0.08); 
            --success: #10b981;
            --warning: #f59e0b;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        main { flex: 1; padding: 2.5rem 4rem; overflow-y: auto; }

        /* --- Header & Search --- */
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .header-flex h1 { font-family: 'Outfit', sans-serif; font-size: 2.5rem; margin: 0; letter-spacing: -1.5px; }

        .search-area { display: flex; gap: 1rem; align-items: center; }
        .search-input {
            background: var(--surface); border: 1px solid var(--border);
            padding: 12px 20px 12px 45px; border-radius: 16px; color: white;
            width: 320px; outline: none; position: relative;
        }
        .search-wrapper { position: relative; }
        .search-wrapper i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }

        .btn-invite { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 16px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s; }
        .btn-invite:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }

        /* --- Grid de Cards --- */
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 1rem;
        }

        .user-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 2rem;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .user-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* Avatar & Status */
        .card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; }
        
        .avatar-box { position: relative; width: 80px; height: 80px; }
        .avatar-main { 
            width: 100%; height: 100%; border-radius: 24px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 800; color: white;
            background: var(--primary);
        }
        
        /* Efecto Glow Online */
        .status-ring {
            position: absolute; inset: -4px; border-radius: 28px;
            border: 2px solid transparent;
        }
        .online .status-ring { 
            border-color: var(--success); 
            animation: pulse-ring 2s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 0.4; }
            100% { transform: scale(1); opacity: 0.8; }
        }

        /* Información del Usuario */
        .user-name { font-family: 'Outfit'; font-size: 1.4rem; margin: 0; color: #fff; }
        .user-role { color: var(--text-muted); font-size: 0.85rem; margin: 4px 0 1.5rem 0; font-weight: 500; }

        /* Badges */
        .plan-pill {
            padding: 6px 14px; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.5px;
            background: rgba(255,255,255,0.05);
        }
        .enterprise { color: #818cf8; background: rgba(129, 140, 248, 0.1); }
        .pro { color: #38bdf8; background: rgba(56, 189, 248, 0.1); }

        /* Stats Rápidos */
        .card-stats {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--border);
        }
        .stat-item span { display: block; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; }
        .stat-item b { font-size: 1rem; color: #fff; }

        /* Menú de Acciones */
        .action-dots {
            background: rgba(255,255,255,0.05); border: none; color: white;
            width: 35px; height: 35px; border-radius: 10px; cursor: pointer;
            transition: 0.3s;
        }
        .action-dots:hover { background: var(--primary); }

    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="header-flex">
            <div>
                <h1>Directorio de Miembros</h1>
                <p style="color: var(--text-muted); margin: 5px 0 0 0;">Gestioná y monitoreá a los usuarios de la comunidad.</p>
            </div>
            <div class="search-area">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-input" placeholder="Buscar por nombre o ID...">
                </div>
                <button class="btn-invite"><i class="fas fa-plus"></i> Invitar</button>
            </div>
        </div>

        <div class="user-grid">
            
            <div class="user-card online">
                <div class="card-header">
                    <div class="avatar-box">
                        <div class="status-ring"></div>
                        <div class="avatar-main">TG</div>
                    </div>
                    <button class="action-dots"><i class="fas fa-ellipsis-h"></i></button>
                </div>
                <h3 class="user-name">Tomás González</h3>
                <p class="user-role">Fullstack Admin • ID: #001</p>
                <span class="plan-pill enterprise">Enterprise</span>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <span>Reservas</span>
                        <b>14 Activas</b>
                    </div>
                    <div class="stat-item">
                        <span>Últ. Login</span>
                        <b>En línea</b>
                    </div>
                </div>
            </div>

            <div class="user-card online">
                <div class="card-header">
                    <div class="avatar-box">
                        <div class="status-ring"></div>
                        <div class="avatar-main" style="background: #ef4444;">LM</div>
                    </div>
                    <button class="action-dots"><i class="fas fa-ellipsis-h"></i></button>
                </div>
                <h3 class="user-name">Lionel Messi</h3>
                <p class="user-role">GOAT • ID: #010</p>
                <span class="plan-pill enterprise">Enterprise</span>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <span>Reservas</span>
                        <b>45 Activas</b>
                    </div>
                    <div class="stat-item">
                        <span>Últ. Login</span>
                        <b>En línea</b>
                    </div>
                </div>
            </div>

            <div class="user-card">
                <div class="card-header">
                    <div class="avatar-box">
                        <div class="avatar-main" style="background: #0ea5e9;">LS</div>
                    </div>
                    <button class="action-dots"><i class="fas fa-ellipsis-h"></i></button>
                </div>
                <h3 class="user-name">Luis Scaloni</h3>
                <p class="user-role">Estratega • ID: #102</p>
                <span class="plan-pill pro">Pro Individual</span>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <span>Reservas</span>
                        <b>8 Activas</b>
                    </div>
                    <div class="stat-item">
                        <span>Últ. Login</span>
                        <b>Ayer, 18:45</b>
                    </div>
                </div>
            </div>

            <div class="user-card online">
                <div class="card-header">
                    <div class="avatar-box">
                        <div class="status-ring"></div>
                        <div class="avatar-main" style="background: #f59e0b;">AR</div>
                    </div>
                    <button class="action-dots"><i class="fas fa-ellipsis-h"></i></button>
                </div>
                <h3 class="user-name">Antonela Roccuzzo</h3>
                <p class="user-role">Fashion Designer • ID: #103</p>
                <span class="plan-pill pro">Pro Individual</span>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <span>Reservas</span>
                        <b>22 Activas</b>
                    </div>
                    <div class="stat-item">
                        <span>Últ. Login</span>
                        <b>En línea</b>
                    </div>
                </div>
            </div>

            <div class="user-card">
                <div class="card-header">
                    <div class="avatar-box">
                        <div class="avatar-main" style="background: #94a3b8;">SM</div>
                    </div>
                    <button class="action-dots"><i class="fas fa-ellipsis-h"></i></button>
                </div>
                <h3 class="user-name">Santi Maratea</h3>
                <p class="user-role">Social Influencer • ID: #104</p>
                <span class="plan-pill">Starter</span>
                
                <div class="card-stats">
                    <div class="stat-item">
                        <span>Reservas</span>
                        <b>2 Activas</b>
                    </div>
                    <div class="stat-item">
                        <span>Últ. Login</span>
                        <b>Hace 3 días</b>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>