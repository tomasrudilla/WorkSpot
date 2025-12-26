<?php 
    // Lógica de sesión y datos simulados para Tomás
    $currentPage = basename($_SERVER['PHP_SELF']);
    $userName = "Tomás González";
    $userLevel = "Miembro Elite";
    $currentIsland = "Alpha-01";
    $currentSeat = "Silla 04";
    $islandTeam = [
        ['nom' => 'Micaela Sanchez', 'puesto' => 'Silla 01', 'rol' => 'UX Design', 'status' => 'online', 'color' => '#818cf8'],
        ['nom' => 'Lucas Ferro', 'puesto' => 'Silla 02', 'rol' => 'Backend', 'status' => 'online', 'color' => '#10b981'],
        ['nom' => 'Disponible', 'puesto' => 'Silla 03', 'rol' => 'N/A', 'status' => 'free', 'color' => '#334155']
    ];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Digital Command Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- ARQUITECTURA DE VARIABLES Y RESET --- */
        :root {
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --accent: #00f2ff;
            --bg: #030712;
            --surface: rgba(15, 23, 42, 0.5);
            --surface-bright: rgba(30, 41, 59, 0.7);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --sidebar-width: 290px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg); 
            color: var(--text-main); 
            display: flex; 
            min-height: 100vh; 
            overflow-x: hidden;
            background-image: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                              radial-gradient(circle at 90% 80%, rgba(0, 242, 255, 0.03) 0%, transparent 50%);
        }

        /* --- CONTENEDOR PRINCIPAL --- */
        main { 
            flex: 1; 
            margin-left: var(--sidebar-width); 
            padding: 2.5rem 3.5rem; 
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- HEADER & LIVE BAR --- */
        .live-status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border);
            margin: -2.5rem -3.5rem 2.5rem -3.5rem;
            padding: 1rem 3.5rem;
            backdrop-filter: blur(20px);
        }

        .live-item { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
        .live-item i { color: var(--accent); }
        .live-indicator { width: 8px; height: 8px; border-radius: 50%; background: var(--success); box-shadow: 0 0 10px var(--success); animation: pulse 2s infinite; }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

        .welcome-section h1 { font-family: 'Outfit'; font-size: 3rem; letter-spacing: -1.5px; margin-bottom: 5px; }
        .welcome-section p { color: var(--text-muted); font-size: 1.1rem; }

        /* --- GRID SYSTEM --- */
        .layout-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 2.5rem;
            margin-top: 2rem;
        }

        /* --- SECCIÓN 1: MI PUESTO ACTUAL --- */
        .puesto-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border-radius: 40px;
            padding: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }

        .puesto-card::before {
            content: ''; position: absolute; top: -50px; right: -50px; width: 300px; height: 300px;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%); opacity: 0.15;
        }

        .puesto-meta { display: flex; gap: 20px; margin-bottom: 2rem; }
        .meta-pill { background: rgba(0, 242, 255, 0.15); color: var(--accent); padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; border: 1px solid var(--accent); }

        .puesto-title { font-family: 'Outfit'; font-size: 3.5rem; margin-bottom: 10px; line-height: 1; }
        .puesto-subtitle { font-size: 1.4rem; color: rgba(255,255,255,0.7); margin-bottom: 2.5rem; }

        .actions-row { display: flex; gap: 15px; }
        .btn-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white; padding: 14px 28px; border-radius: 18px;
            font-weight: 700; cursor: pointer; transition: 0.3s;
            backdrop-filter: blur(10px);
        }
        .btn-glass:hover { background: white; color: black; transform: translateY(-3px); }

        /* --- SECCIÓN 2: MI ISLA (TEAM & LAYOUT) --- */
        .island-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 35px;
            padding: 2.5rem;
            margin-top: 2.5rem;
        }

        .island-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .island-header h3 { font-family: 'Outfit'; font-size: 1.5rem; }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .member-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            padding: 1.5rem;
            border-radius: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: 0.3s;
        }

        .member-card:hover { border-color: var(--primary); background: rgba(99, 102, 241, 0.05); }

        .member-avatar {
            width: 60px; height: 60px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: white; margin-bottom: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .member-name { font-weight: 700; font-size: 0.95rem; margin-bottom: 4px; }
        .member-seat { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; }

        /* --- SECCIÓN 3: DISPOSITIVOS Y HARDWARE --- */
        .hardware-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 35px;
            padding: 2rem;
        }

        .device-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
        }

        .device-item:last-child { border: none; }

        .device-info { display: flex; align-items: center; gap: 15px; }
        .device-icon { width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); }

        .device-label p { font-weight: 600; font-size: 0.9rem; }
        .device-label span { font-size: 0.75rem; color: var(--text-muted); }

        .device-toggle {
            width: 40px; height: 20px; background: var(--success); border-radius: 20px; position: relative; cursor: pointer;
        }
        .device-toggle::after { content: ''; position: absolute; right: 3px; top: 3px; width: 14px; height: 14px; background: white; border-radius: 50%; }

        /* --- SECCIÓN 4: ANALYTICS Y RED --- */
        .network-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 35px;
            padding: 2rem;
            margin-top: 2rem;
        }

        .bandwidth-chart {
            height: 120px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            margin-top: 1.5rem;
        }

        .band-bar {
            flex: 1;
            background: var(--accent);
            border-radius: 4px 4px 0 0;
            opacity: 0.3;
            transition: 0.5s ease;
        }

        .band-bar.active { opacity: 1; box-shadow: 0 0 15px var(--accent); }

        /* --- RIGHT COLUMN WIDGETS --- */
        .widget-stack { display: flex; flex-direction: column; gap: 2rem; }

        .wallet-widget {
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            border: 1px solid var(--border);
            border-radius: 40px;
            padding: 2.5rem;
            text-align: center;
        }

        .notification-feed {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 35px;
            padding: 2rem;
        }

        .noti-item {
            display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid var(--border);
        }

        .noti-item:last-child { border: none; }

        .noti-icon { width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

        /* --- UTILS --- */
        .btn-primary { width: 100%; padding: 18px; background: var(--primary); color: white; border: none; border-radius: 20px; font-weight: 800; cursor: pointer; transition: 0.3s; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 25px var(--primary-glow); }

    </style>
</head>
<body>

    <?php include '../includes/sidebar_user.php'; ?>

    <main>
        <div class="live-status-bar">
            <div style="display: flex; gap: 30px;">
                <div class="live-item"><i class="fas fa-clock"></i> <span id="clock">19:03:54</span></div>
                <div class="live-item"><i class="fas fa-wifi"></i> <span>WS-Enterprise-A (850Mbps)</span></div>
                <div class="live-item"><i class="fas fa-temperature-half"></i> <span>Clima: 22°C</span></div>
            </div>
            <div class="live-item">
                <div class="live-indicator"></div>
                <span style="color: var(--success); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;">Conexión Segura Activa</span>
            </div>
        </div>

        <div class="welcome-section">
            <h1 id="greeting">¡Hola, <?php echo explode(' ', $userName)[0]; ?>! 👋</h1>
            <p>Tenés asignada la <b><?php echo $currentSeat; ?></b> en la <b>Isla <?php echo $currentIsland; ?></b>.</p>
        </div>

        <div class="layout-grid">
            
            <div class="left-col">
                
                <div class="puesto-card">
                    <div class="puesto-meta">
                        <span class="meta-pill">Sesión Activa</span>
                        <span class="meta-pill" style="background: rgba(16,185,129,0.1); color: var(--success); border-color: var(--success);">Nivel de Acceso: Elite</span>
                    </div>
                    <h2 class="puesto-title">Isla <?php echo $currentIsland; ?></h2>
                    <p class="puesto-subtitle"><?php echo $currentSeat; ?> • Sector Desarrolladores</p>
                    
                    <div class="actions-row">
                        <button class="btn-glass" onclick="location.href='my_access.php'"><i class="fas fa-key"></i> Abrir con NFC</button>
                        <button class="btn-glass"><i class="fas fa-headset"></i> Solicitar Soporte</button>
                        <button class="btn-glass" style="background: rgba(239,68,68,0.1); color: var(--danger); border-color: var(--danger);">Finalizar Turno</button>
                    </div>
                </div>

                <div class="island-section">
                    <div class="island-header">
                        <h3>Compañeros de Isla</h3>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Sincronizado con Recursos Humanos</span>
                    </div>
                    
                    <div class="team-grid">
                        <div class="member-card" style="border-color: var(--primary);">
                            <div class="member-avatar" style="background: var(--primary);">TG</div>
                            <div class="member-name"><?php echo $userName; ?></div>
                            <div class="member-seat" style="color: var(--primary);"><?php echo $currentSeat; ?></div>
                        </div>

                        <?php foreach($islandTeam as $member): ?>
                        <div class="member-card">
                            <div class="member-avatar" style="background: <?php echo $member['color']; ?>; opacity: <?php echo ($member['status'] == 'free' ? '0.3' : '1'); ?>">
                                <?php echo ($member['status'] == 'free' ? '?' : implode('', array_map(fn($n) => $n[0], explode(' ', $member['nom'])))); ?>
                            </div>
                            <div class="member-name"><?php echo $member['nom']; ?></div>
                            <div class="member-seat"><?php echo $member['puesto']; ?></div>
                            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px;"><?php echo $member['rol']; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="network-card">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-family: 'Outfit';">Ancho de Banda en Tiempo Real</h3>
                        <div style="text-align: right;">
                            <b style="color: var(--accent); font-size: 1.2rem;">12.4 MB/s</b>
                            <p style="font-size: 0.7rem; color: var(--text-muted);">Uso de Silla 04</p>
                        </div>
                    </div>
                    <div class="bandwidth-chart">
                        <div class="band-bar" style="height: 40%;"></div>
                        <div class="band-bar" style="height: 60%;"></div>
                        <div class="band-bar active" style="height: 85%;"></div>
                        <div class="band-bar active" style="height: 95%;"></div>
                        <div class="band-bar active" style="height: 70%;"></div>
                        <div class="band-bar" style="height: 50%;"></div>
                        <div class="band-bar" style="height: 30%;"></div>
                        <div class="band-bar" style="height: 45%;"></div>
                        <div class="band-bar active" style="height: 80%;"></div>
                        <div class="band-bar active" style="height: 100%;"></div>
                    </div>
                </div>

            </div>

            <div class="right-col">
                <div class="widget-stack">
                    
                    <div class="hardware-card">
                        <h3 style="font-family: 'Outfit'; margin-bottom: 1.5rem;">Mis Dispositivos</h3>
                        
                        <div class="device-item">
                            <div class="device-info">
                                <div class="device-icon"><i class="fas fa-desktop"></i></div>
                                <div class="device-label">
                                    <p>Dual Monitor 27"</p>
                                    <span>Samsung Odyssey G5</span>
                                </div>
                            </div>
                            <div class="device-toggle"></div>
                        </div>

                        <div class="device-item">
                            <div class="device-info">
                                <div class="device-icon"><i class="fas fa-plug"></i></div>
                                <div class="device-label">
                                    <p>Docking Station</p>
                                    <span>Thunderbolt 4 Hub</span>
                                </div>
                            </div>
                            <div class="device-toggle"></div>
                        </div>

                        <div class="device-item">
                            <div class="device-info">
                                <div class="device-icon"><i class="fas fa-keyboard"></i></div>
                                <div class="device-label">
                                    <p>Teclado Mecánico</p>
                                    <span>Keychron K2 (V2)</span>
                                </div>
                            </div>
                            <div style="font-size: 0.7rem; color: var(--success); font-weight: 800;">CONECTADO</div>
                        </div>

                        <div class="device-item">
                            <div class="device-info">
                                <div class="device-icon" style="color: var(--warning);"><i class="fas fa-lightbulb"></i></div>
                                <div class="device-label">
                                    <p>Luz de Escritorio</p>
                                    <span>LED Regulable 4000K</span>
                                </div>
                            </div>
                            <div class="device-toggle"></div>
                        </div>
                    </div>

                    <div class="wallet-widget">
                        <p style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Tiempo Disponible</p>
                        <h2 style="font-family: 'Outfit'; font-size: 4rem; margin: 10px 0;">124 <span style="font-size: 1.5rem; color: var(--accent);">hrs</span></h2>
                        <div style="background: rgba(16,185,129,0.1); color: var(--success); padding: 8px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; margin-bottom: 2rem;">
                            <i class="fas fa-caret-up"></i> +4.2% respecto al mes pasado
                        </div>
                        <button class="btn-primary" onclick="location.href='reserve_space.php'">Recargar Créditos</button>
                    </div>

                    <div class="notification-feed">
                        <h3 style="font-family: 'Outfit'; margin-bottom: 1.5rem;">Avisos del Edificio</h3>
                        
                        <div class="noti-item">
                            <div class="noti-icon" style="background: rgba(239,68,68,0.1); color: var(--danger);"><i class="fas fa-triangle-exclamation"></i></div>
                            <div>
                                <p style="font-size: 0.85rem; font-weight: 700;">Ascensor B fuera de servicio</p>
                                <span style="font-size: 0.7rem; color: var(--text-muted);">Mantenimiento técnico hasta las 20:00hs.</span>
                            </div>
                        </div>

                        <div class="noti-item">
                            <div class="noti-icon" style="background: rgba(16,185,129,0.1); color: var(--success);"><i class="fas fa-utensils"></i></div>
                            <div>
                                <p style="font-size: 0.85rem; font-weight: 700;">Menú del día disponible</p>
                                <span style="font-size: 0.7rem; color: var(--text-muted);">Pastas caseras en el buffet del piso 3.</span>
                            </div>
                        </div>

                        <div class="noti-item">
                            <div class="noti-icon" style="background: rgba(0, 242, 255, 0.1); color: var(--accent);"><i class="fas fa-calendar-star"></i></div>
                            <div>
                                <p style="font-size: 0.85rem; font-weight: 700;">After Office: Viernes 19hs</p>
                                <span style="font-size: 0.7rem; color: var(--text-muted);">En el Lounge Creativo para todos los miembros.</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script>
        // Reloj en tiempo real
        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('clock');
            if(clock) {
                clock.textContent = now.toLocaleTimeString('es-AR', { hour12: false });
            }
        }
        setInterval(updateClock, 1000);

        // Saludo dinámico según hora
        const hour = new Date().getHours();
        const greeting = document.getElementById('greeting');
        if(hour < 12) greeting.innerHTML = "¡Buen día, Tomás! ☕";
        else if(hour < 20) greeting.innerHTML = "¡Buenas tardes, Tomás! 🚀";
        else greeting.innerHTML = "¡Buenas noches, Tomás! 🌙";

        // Simulación de actividad de barras de red
        const bars = document.querySelectorAll('.band-bar');
        setInterval(() => {
            bars.forEach(bar => {
                const height = Math.floor(Math.random() * 70) + 30;
                bar.style.height = height + '%';
                if(height > 70) bar.classList.add('active');
                else bar.classList.remove('active');
            });
        }, 3000);
    </script>
</body>
</html>