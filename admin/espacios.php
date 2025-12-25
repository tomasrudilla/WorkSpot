<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Gestión de Espacios</title>
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
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        main { flex: 1; padding: 2.5rem 4rem; overflow-y: auto; }

        /* --- Toolbar Superior --- */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            background: var(--surface);
            padding: 0.5rem;
            border-radius: 14px;
            border: 1px solid var(--border);
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
        }

        /* --- Grid de Espacios --- */
        .grid-spaces { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 2rem; 
            margin-top: 1rem; 
        }

        .space-card { 
            background: var(--surface); 
            border-radius: 24px; 
            border: 1px solid var(--border); 
            overflow: hidden; 
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .space-card:hover { 
            transform: translateY(-10px); 
            border-color: var(--primary); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .space-img { 
            height: 200px; 
            position: relative; 
        }

        /* Badge de Estado */
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(5px);
        }

        .status-available { background: rgba(16, 185, 129, 0.2); color: var(--success); border: 1px solid var(--success); }
        .status-occupied { background: rgba(239, 68, 68, 0.2); color: var(--danger); border: 1px solid var(--danger); }
        .status-maint { background: rgba(245, 158, 11, 0.2); color: var(--warning); border: 1px solid var(--warning); }

        .space-content { padding: 1.5rem; }
        
        .space-content h3 { 
            font-family: 'Outfit', sans-serif; 
            font-size: 1.4rem; 
            margin: 0 0 0.5rem 0; 
        }

        .amenities {
            display: flex;
            gap: 12px;
            margin: 1rem 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .price { 
            color: var(--primary); 
            font-weight: 800; 
            font-size: 1.3rem; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add { 
            background: var(--primary); 
            color: white; 
            border: none; 
            padding: 12px 24px; 
            border-radius: 14px; 
            font-weight: 700; 
            cursor: pointer; 
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-add:hover { opacity: 0.9; transform: scale(1.02); }

        .btn-edit {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 8px;
            border-radius: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="toolbar">
            <div>
                <h1 style="font-family: 'Outfit'; font-size: 2.2rem;">Gestión de Espacios</h1>
                <p style="color: var(--text-muted);">Administra y monitorea la disponibilidad en tiempo real.</p>
            </div>
            <button class="btn-add"><i class="fas fa-plus"></i> Nuevo Espacio</button>
        </div>

        <div class="filter-group">
            <button class="filter-btn active">Todos</button>
            <button class="filter-btn">Oficinas</button>
            <button class="filter-btn">Salas de Juntas</button>
            <button class="filter-btn">Escritorios</button>
            <button class="filter-btn">Mantenimiento</button>
        </div>

        <div class="grid-spaces">
            
            <div class="space-card">
                <div class="space-img" style="background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=600') center/cover;">
                    <span class="status-badge status-available">Disponible</span>
                </div>
                <div class="space-content">
                    <h3>Sala Silicon Valley</h3>
                    <p style="color:var(--text-muted); font-size: 0.9rem;">Perfecta para presentaciones y cierres de contratos.</p>
                    <div class="amenities">
                        <i class="fas fa-tv" title="4K TV"></i>
                        <i class="fas fa-wifi" title="WiFi 6G"></i>
                        <i class="fas fa-coffee" title="Café Libre"></i>
                        <i class="fas fa-users" title="Cap: 12"></i>
                    </div>
                    <div class="price">
                        $55.00/h
                        <button class="btn-edit"><i class="fas fa-pen"></i></button>
                    </div>
                </div>
            </div>

            <div class="space-card">
                <div class="space-img" style="background: url('https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?w=600') center/cover;">
                    <span class="status-badge status-occupied">Ocupado</span>
                </div>
                <div class="space-content">
                    <h3>Desk Individual 04</h3>
                    <p style="color:var(--text-muted); font-size: 0.9rem;">Zona silenciosa ideal para programadores y analistas.</p>
                    <div class="amenities">
                        <i class="fas fa-desktop" title="Monitor 27'"></i>
                        <i class="fas fa-plug" title="Carga Rápida"></i>
                        <i class="fas fa-headset" title="Zona Silencio"></i>
                    </div>
                    <div class="price">
                        $15.00/h
                        <button class="btn-edit"><i class="fas fa-pen"></i></button>
                    </div>
                </div>
            </div>

            <div class="space-card">
                <div class="space-img" style="background: url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=600') center/cover;">
                    <span class="status-badge status-available">Disponible</span>
                </div>
                <div class="space-content">
                    <h3>Oficina "Elon Musk"</h3>
                    <p style="color:var(--text-muted); font-size: 0.9rem;">Privacidad absoluta con vista panorámica a la ciudad.</p>
                    <div class="amenities">
                        <i class="fas fa-shield-alt" title="Acceso Privado"></i>
                        <i class="fas fa-couch" title="Living Privado"></i>
                        <i class="fas fa-snowflake" title="Climatización"></i>
                    </div>
                    <div class="price">
                        $120.00/h
                        <button class="btn-edit"><i class="fas fa-pen"></i></button>
                    </div>
                </div>
            </div>

            <div class="space-card">
                <div class="space-img" style="background: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=600') center/cover;">
                    <span class="status-badge status-maint">En Limpieza</span>
                </div>
                <div class="space-content">
                    <h3>Sala de Podcast</h3>
                    <p style="color:var(--text-muted); font-size: 0.9rem;">Tratamiento acústico profesional y micrófonos de alta gama.</p>
                    <div class="amenities">
                        <i class="fas fa-microphone" title="Shure SM7B"></i>
                        <i class="fas fa-volume-up" title="Acustizado"></i>
                        <i class="fas fa-video" title="Cámaras 4K"></i>
                    </div>
                    <div class="price">
                        $40.00/h
                        <button class="btn-edit"><i class="fas fa-pen"></i></button>
                    </div>
                </div>
            </div>

            <div class="space-card">
                <div class="space-img" style="background: url('https://images.unsplash.com/photo-1590247813693-5541d1c609fd?w=600') center/cover;">
                    <span class="status-badge status-available">Disponible</span>
                </div>
                <div class="space-content">
                    <h3>Box de Llamadas 02</h3>
                    <p style="color:var(--text-muted); font-size: 0.9rem;">Pequeño habitáculo insonorizado para videollamadas rápidas.</p>
                    <div class="amenities">
                        <i class="fas fa-door-closed" title="Insonoro"></i>
                        <i class="fas fa-lightbulb" title="Luz de Video"></i>
                    </div>
                    <div class="price">
                        $8.00/h
                        <button class="btn-edit"><i class="fas fa-pen"></i></button>
                    </div>
                </div>
            </div>

            <div class="space-card">
                <div class="space-img" style="background: url('https://images.unsplash.com/photo-1431540015161-0bf868a2d407?w=600') center/cover;">
                    <span class="status-badge status-available">Disponible</span>
                </div>
                <div class="space-content">
                    <h3>Lounge Creativo</h3>
                    <p style="color:var(--text-muted); font-size: 0.9rem;">Espacio abierto para lluvia de ideas y networking casual.</p>
                    <div class="amenities">
                        <i class="fas fa-beer" title="Happy Hour"></i>
                        <i class="fas fa-gamepad" title="Zona PS5"></i>
                        <i class="fas fa-users" title="Cap: 20"></i>
                    </div>
                    <div class="price">
                        $30.00/h
                        <button class="btn-edit"><i class="fas fa-pen"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>