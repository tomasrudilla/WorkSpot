<?php 
    // Obtenemos el nombre del archivo actual para saber qué item activar
    $currentPage = basename($_SERVER['PHP_SELF']); 
    // Capturamos el nombre de la sala si estamos en el mapa
    $salaNombre = isset($_GET['sala']) ? $_GET['sala'] : 'Sector Alpha-01';
?>
<style>
    :root {
        --primary: #6366f1;
        --primary-glow: rgba(99, 102, 241, 0.5);
        --accent: #06b6d4;
        /* Fondo más profundo con un gradiente sutil */
        --bg-sidebar: linear-gradient(165deg, #0b1120 0%, #030712 100%);
        --border-glass-faint: rgba(255, 255, 255, 0.04);
        --border-glass-strong: rgba(255, 255, 255, 0.1);
        --text-main: #ffffff;
        --text-muted: #64748b;
        --grad-brand: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        --danger-soft: rgba(248, 113, 113, 0.08);
        --danger-solid: #ef4444;
    }

    /* --- Estructura Principal --- */
    .user-sidebar {
        width: 260px; height: 100vh;
        background: var(--bg-sidebar);
        /* Efecto de cristal esmerilado profundo */
        backdrop-filter: blur(30px) saturate(150%);
        border-right: 1px solid var(--border-glass-faint);
        /* Sombra interna para dar volumen */
        box-shadow: inset -1px 0 0 var(--border-glass-faint);
        display: flex; flex-direction: column;
        padding: 2rem 1.2rem;
        position: fixed; left: 0; top: 0; z-index: 2000;
        box-sizing: border-box;
    }

    /* --- Logo Premium --- */
    .logo-wrapper { 
        display: flex; align-items: center; gap: 12px; 
        margin-bottom: 2.5rem; text-decoration: none; padding-left: 4px;
    }
    .logo-brand-icon { 
        width: 40px; height: 40px; 
        background: var(--grad-brand); 
        border-radius: 12px; display: flex; align-items: center; justify-content: center; 
        color: white; font-size: 1.2rem;
        /* Resplandor suave debajo del icono */
        box-shadow: 0 10px 25px -5px var(--primary-glow);
    }
    .logo-brand-text { 
        font-family: 'Outfit'; font-size: 1.5rem; font-weight: 800; 
        /* Texto con gradiente */
        background: var(--grad-brand); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px; 
    }

    /* --- Navegación --- */
    .nav-list { display: flex; flex-direction: column; gap: 8px; flex: 1; }
    
    .nav-item { 
        display: flex; align-items: center; gap: 14px; padding: 1rem 1.2rem; 
        color: var(--text-muted); text-decoration: none; border-radius: 16px; 
        font-weight: 600; font-size: 0.95rem; 
        border: 1px solid transparent; /* Preparamos el borde para el hover */
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Transición suave y curva */
        position: relative; overflow: hidden;
    }
    .nav-item i { font-size: 1.1rem; transition: 0.3s; color: #4b5563; }

    /* Hover State */
    .nav-item:hover { 
        color: var(--text-main);
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--border-glass-faint);
        transform: translateX(6px); /* Movimiento más notorio */
    }
    .nav-item:hover i { color: var(--text-main); }

    /* ACTIVE STATE: La magia ocurre aquí */
    .nav-item.active { 
        background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: white;
        /* Luz interna */
        box-shadow: inset 0 0 20px rgba(99, 102, 241, 0.05);
    }
    .nav-item.active i { color: var(--primary); filter: drop-shadow(0 0 8px var(--primary)); }

    /* La barra de luz lateral (Light Leak) */
    .nav-item.active::before {
        content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px;
        background: var(--primary);
        box-shadow: 0 0 25px 5px var(--primary);
        border-radius: 0 4px 4px 0;
    }

    /* Sub-item (Mapa) */
    .nav-sub-item {
        padding-left: 3.8rem; margin-bottom: 10px; margin-top: -5px;
        display: flex; align-items: center; gap: 8px; color: var(--primary);
        font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn { from {opacity:0; transform: translateX(-10px);} to {opacity:1; transform: translateX(0);} }

    /* --- Footer Section --- */
    .sidebar-bottom { 
        margin-top: auto; padding-top: 1.5rem; 
        display: flex; flex-direction: column; gap: 15px;
    }

    /* Tarjeta de Perfil Flotante */
    .profile-pill { 
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
        border: 1px solid var(--border-glass-strong); 
        padding: 1rem; border-radius: 20px; 
        display: flex; align-items: center; gap: 12px;
        /* Sombra para que parezca flotar */
        box-shadow: 0 10px 20px -10px rgba(0,0,0,0.5);
        transition: 0.3s;
    }
    .profile-pill:hover { border-color: rgba(255,255,255,0.2); transform: translateY(-2px); }

    .user-avatar-circle { 
        width: 38px; height: 38px; border-radius: 10px; 
        background: var(--grad-brand); display: flex; 
        align-items: center; justify-content: center; 
        font-family: 'Outfit'; font-weight: 800; color: white; font-size: 0.9rem;
        box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
    }

    /* Botón Logout Elegante */
    .btn-logout { 
        display: flex; align-items: center; justify-content: center; gap: 10px; 
        padding: 1rem; border-radius: 16px; color: #f87171; 
        text-decoration: none; font-weight: 700; font-size: 0.9rem;
        background: var(--danger-soft); 
        border: 1px solid rgba(248, 113, 113, 0.15);
        transition: all 0.3s ease;
    }
    .btn-logout:hover { 
        background: var(--danger-solid); color: white; 
        border-color: var(--danger-solid);
        box-shadow: 0 5px 20px rgba(239, 68, 68, 0.3);
        transform: translateY(-2px);
    }
</style>

<aside class="user-sidebar">
    <a href="home_user.php" class="logo-wrapper">
        <div class="logo-brand-icon"><i class="fas fa-rocket"></i></div>
        <span class="logo-brand-text">WorkSpot</span>
    </a>

    <nav class="nav-list">
        <a href="home_user.php" class="nav-item <?= $currentPage == 'home_user.php' ? 'active' : '' ?>">
            <i class="fas fa-grid-2"></i> Inicio
        </a>
        
        <a href="reserve_space.php" class="nav-item <?= ($currentPage == 'reserve_space.php' || $currentPage == 'map_reserve.php') ? 'active' : '' ?>">
            <i class="fas fa-map-location-dot"></i> Reservar
        </a>
        
        <?php if($currentPage == 'map_reserve.php'): ?>
        <div class="nav-sub-item">
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span><?= htmlspecialchars($salaNombre) ?></span>
        </div>
        <?php endif; ?>

        <a href="my_reserve.php" class="nav-item <?= $currentPage == 'my_reserve.php' ? 'active' : '' ?>">
            <i class="fas fa-calendar-days"></i> Mis Reservas
        </a>
        
        <a href="my_access.php" class="nav-item <?= $currentPage == 'my_access.php' ? 'active' : '' ?>">
            <i class="fas fa-fingerprint"></i> Mi Acceso
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="profile-pill">
            <div class="user-avatar-circle">TG</div>
            <div style="line-height: 1.3;">
                <p style="font-size: 0.9rem; color: #fff; font-weight: 700; margin:0;">Tomás G.</p>
                <p style="font-size: 0.7rem; color: var(--accent); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin:0;">Elite Member</p>
            </div>
        </div>
        <a href="../login.php" class="btn-logout">
            <i class="fas fa-power-off"></i> 
            Cerrar Sesión
        </a>
    </div>
</aside>