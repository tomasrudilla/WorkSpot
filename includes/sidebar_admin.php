<style>
    :root {
        --side-bg: #0b1120; /* Azul noche profundo */
        --side-border: rgba(255, 255, 255, 0.05);
        --item-hover: rgba(99, 102, 241, 0.08);
        --active-gradient: linear-gradient(90deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 100%);
    }

    .sidebar {
        width: 290px;
        background: var(--side-bg);
        border-right: 1px solid var(--side-border);
        display: flex;
        flex-direction: column;
        padding: 2rem 1.25rem;
        height: 100vh;
        position: sticky;
        top: 0;
    }

    /* --- ÁREA DE USUARIO (HARDCODEADA) --- */
    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--side-border);
        border-radius: 18px;
        margin-bottom: 2.5rem;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        background: var(--gradient); /* Usando el gradiente del global */
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .user-info h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        margin: 0;
        color: #fff;
    }

    .user-info span {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: block;
    }

    /* --- LOGO --- */
    .logo-container {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 2.5rem;
        padding-left: 0.5rem;
    }

    .logo-text {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -1px;
    }

    /* --- NAVEGACIÓN --- */
    .nav-group { margin-bottom: 2rem; }
    
    .nav-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 0 0 1rem 1rem;
        opacity: 0.5;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 0.85rem 1.1rem;
        color: var(--text-muted);
        text-decoration: none;
        border-radius: 14px;
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 4px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    /* Estado Activo */
    .nav-link.active {
        color: var(--primary);
        background: var(--active-gradient);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        left: 0;
        width: 4px;
        height: 20px;
        background: var(--primary);
        border-radius: 0 10px 10px 0;
        box-shadow: 2px 0 10px var(--primary);
    }

    .nav-link i {
        font-size: 1.15rem;
        width: 24px;
        text-align: center;
    }

    .nav-link:hover:not(.active) {
        background: var(--item-hover);
        color: #fff;
        transform: translateX(4px);
    }

    /* --- FOOTER --- */
    .sidebar-footer {
        margin-top: auto;
        border-top: 1px solid var(--side-border);
        padding-top: 1.5rem;
    }

    .appearance-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.02);
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ef4444;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        transition: 0.3s;
    }

    .logout-btn:hover { background: rgba(239, 68, 68, 0.08); }
</style>

<aside class="sidebar">
    <div class="logo-container">
        <div style="width: 35px; height: 35px; background: var(--gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
            <i class="fas fa-layer-group"></i>
        </div>
        <span class="logo-text">WorkSpot</span>
    </div>

    <div class="user-profile">
        <div class="user-avatar">TG</div>
        <div class="user-info">
            <h4>Tomás González</h4>
            <span>Fullstack Admin</span>
        </div>
    </div>

    <nav class="nav-menu">
        <div class="nav-group">
            <p class="nav-label">Principal</p>
            <a href="admin_home.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'admin_home.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="espacios.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'espacios.php' ? 'active' : '' ?>">
                <i class="fas fa-building-user"></i> Espacios
            </a>
            <a href="reservas.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'reservas.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i> Reservas
            </a>
            
        </div>

        <div class="nav-group">
            <p class="nav-label">Comunidad</p>
            <a href="usuarios.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'usuarios.php' ? 'active' : '' ?>">
                <i class="fas fa-user-group"></i> Usuarios
            </a>
            <a href="soporte.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'soporte.php' ? 'active' : '' ?>">
                <i class="fas fa-headset"></i> Soporte
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="appearance-box">
            <span style="font-size: 0.8rem; font-weight: 600; opacity: 0.8;">Modo Oscuro</span>
            <div id="themeToggleAdmin" style="cursor: pointer; color: var(--primary);">
                <i class="fas fa-moon"></i>
            </div>
        </div>
        <a href="../login.php" class="logout-btn">
            <i class="fas fa-power-off"></i> Cerrar Sesión
        </a>
    </div>
</aside>