<?php 
    $currentPage = basename($_SERVER['PHP_SELF']); 
    $salaNombre = isset($_GET['sala']) ? $_GET['sala'] : 'Sector Alpha-01';
?>
<style>
    :root {
        --primary: #6366f1;
        --accent: #00f2ff;
        --bg-sidebar: rgba(11, 17, 32, 0.9);
        --border-glass: rgba(255, 255, 255, 0.08);
        --text-muted: #94a3b8;
    }

    .user-sidebar {
        width: 290px; height: 100vh; background: var(--bg-sidebar);
        backdrop-filter: blur(40px) saturate(180%); border-right: 1px solid var(--border-glass);
        display: flex; flex-direction: column; padding: 2.5rem 1.5rem;
        position: fixed; left: 0; top: 0; z-index: 2000;
    }

    .logo-wrapper { display: flex; align-items: center; gap: 15px; margin-bottom: 3.5rem; text-decoration: none; }
    .logo-brand-icon { width: 42px; height: 42px; background: linear-gradient(135deg, var(--primary), var(--accent)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }
    .logo-brand-text { font-family: 'Outfit'; font-size: 1.7rem; font-weight: 800; color: #fff; letter-spacing: -1px; }

    .nav-list { display: flex; flex-direction: column; gap: 8px; flex: 1; }
    .nav-item { display: flex; align-items: center; gap: 14px; padding: 1rem 1.2rem; color: var(--text-muted); text-decoration: none; border-radius: 18px; font-weight: 600; font-size: 0.95rem; transition: 0.3s; position: relative; }
    .nav-item.active { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .nav-item.active::before { content: ''; position: absolute; left: 0; width: 5px; height: 20px; background: var(--primary); border-radius: 0 10px 10px 0; box-shadow: 0 0 15px var(--primary); }

    .nav-sub-item { padding-left: 3.8rem; margin-top: -5px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; color: var(--primary); }

    .sidebar-bottom { margin-top: auto; padding-top: 2rem; border-top: 1px solid var(--border-glass); }
    .profile-pill { background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-glass); padding: 1rem; border-radius: 20px; display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; }
    .user-avatar-circle { width: 42px; height: 42px; border-radius: 12px; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; color: white; }
    .btn-logout { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 0.9rem; border-radius: 15px; color: #ef4444; text-decoration: none; font-weight: 700; background: rgba(239, 68, 68, 0.05); }
</style>

<aside class="user-sidebar">
    <a href="home_user.php" class="logo-wrapper">
        <div class="logo-brand-icon"><i class="fas fa-layer-group"></i></div>
        <span class="logo-brand-text">WorkSpot</span>
    </a>
    <nav class="nav-list">
        <a href="home_user.php" class="nav-item <?= $currentPage == 'home_user.php' ? 'active' : '' ?>"><i class="fas fa-house-user"></i> Mi Inicio</a>
        <a href="reserve_space.php" class="nav-item <?= ($currentPage == 'reserve_space.php' || $currentPage == 'map_reserve.php') ? 'active' : '' ?>"><i class="fas fa-rocket"></i> Reservar</a>
        
        <?php if($currentPage == 'map_reserve.php'): ?>
        <div class="nav-sub-item">
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase;"><?= htmlspecialchars($salaNombre) ?></span>
        </div>
        <?php endif; ?>

        <a href="my_reserve.php" class="nav-item <?= $currentPage == 'my_reserve.php' ? 'active' : '' ?>"><i class="fas fa-calendar-check"></i> Mis Reservas</a>
        <a href="my_access.php" class="nav-item <?= $currentPage == 'my_access.php' ? 'active' : '' ?>"><i class="fas fa-fingerprint"></i> Mi Acceso</a>
    </nav>
    <div class="sidebar-bottom">
        <div class="profile-pill">
            <div class="user-avatar-circle">TG</div>
            <div style="font-size: 0.9rem; color: white; font-weight: 700;">Tomás G. <br><span style="font-size: 0.7rem; color: var(--primary);">Elite Member</span></div>
        </div>
        <a href="../login.php" class="btn-logout"><i class="fas fa-power-off"></i> Cerrar Sesión</a>
    </div>
</aside>