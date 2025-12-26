<?php 
    $currentPage = 'reserve_space.php'; 
    $salaNombre = isset($_GET['sala']) ? $_GET['sala'] : 'Sector Alpha-01';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Plano Interactivo 3D</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --accent: #06b6d4;
            --bg: #030712;
            --surface: #0f172a;
            --border: rgba(255, 255, 255, 0.08);
            --free: #10b981;
            --occupied: #ef4444;
            --text-muted: #64748b;
            --sidebar-width: 260px;
            --grad: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: white; height: 100vh; overflow: hidden; display: flex; }

        /* --- SIDEBAR --- */
        .user-sidebar {
            width: var(--sidebar-width); height: 100vh; background: rgba(3, 7, 18, 0.95);
            backdrop-filter: blur(20px); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; padding: 2rem 1.2rem;
            position: fixed; left: 0; top: 0; z-index: 5000;
        }
        .logo-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 2.5rem; text-decoration: none; }
        .logo-brand-icon { width: 38px; height: 38px; background: var(--grad); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3); }
        .logo-brand-text { font-family: 'Outfit'; font-size: 1.5rem; font-weight: 800; background: var(--grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .nav-list { display: flex; flex-direction: column; gap: 6px; flex: 1; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 0.9rem 1.1rem; color: var(--text-muted); text-decoration: none; border-radius: 14px; font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.03); color: #fff; transform: translateX(5px); }
        .nav-item.active { background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); color: #fff; }

        .sidebar-bottom { margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border); }
        .profile-pill { background: rgba(255, 255, 255, 0.02); padding: 0.8rem; border-radius: 16px; display: flex; align-items: center; gap: 10px; margin-bottom: 12px; border: 1px solid var(--border); }
        .user-avatar-circle { width: 35px; height: 35px; border-radius: 10px; background: var(--grad); display: flex; align-items: center; justify-content: center; font-weight: 800; font-family: 'Outfit'; color: white; }
        
        .btn-logout { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 0.8rem; border-radius: 14px; color: #f87171; text-decoration: none; font-weight: 700; background: rgba(248, 113, 113, 0.05); transition: 0.3s; font-size: 0.85rem; }
        .btn-logout:hover { background: #ef4444; color: white; box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3); }

        /* --- ÁREA 3D --- */
        main { flex: 1; margin-left: var(--sidebar-width); position: relative; background: radial-gradient(circle at center, #111827 0%, #030712 100%); perspective: 3000px; }
        
        #pan-wrapper { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transform-style: preserve-3d; cursor: grab; }
        #pan-wrapper:active { cursor: grabbing; }

        #scene { 
            width: 2000px; height: 1600px; transform-style: preserve-3d; 
            transform: rotateX(55deg) rotateZ(-30deg); 
            will-change: transform;
        }

        .grid-floor { 
            width: 100%; height: 100%; 
            background-image: linear-gradient(var(--border) 1px, transparent 1px), linear-gradient(90deg, var(--border) 1px, transparent 1px); 
            background-size: 80px 80px; border: 2px solid var(--border); 
            position: relative; transform-style: preserve-3d; 
        }

        .island { position: absolute; width: 220px; height: 150px; transform-style: preserve-3d; }
        
        .table-surface { 
            position: absolute; width: 100%; height: 100%; 
            background: #1e293b; border: 2px solid var(--primary); 
            transform: translateZ(50px); border-radius: 15px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 0.75rem; font-weight: 800; cursor: pointer; 
            transition: all 0.3s ease;
            pointer-events: auto;
        }
        .table-surface:hover { background: var(--primary); box-shadow: 0 0 30px var(--primary); color: white; }

        .chair { 
            position: absolute; width: 44px; height: 44px; border-radius: 12px; 
            transform: translateZ(35px); border: 2px solid rgba(255,255,255,0.1); 
            cursor: pointer; transition: all 0.3s ease;
            pointer-events: auto;
        }
        .chair.free { background: var(--free); box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
        .chair.occupied { background: var(--occupied); cursor: not-allowed; opacity: 0.3; }
        .chair:hover.free { border-color: white; transform: translateZ(45px) scale(1.1); box-shadow: 0 0 30px var(--free); }

        .p-t1 { top: -60px; left: 30px; } .p-t2 { top: -60px; left: 140px; }
        .p-b1 { bottom: -60px; left: 30px; } .p-b2 { bottom: -60px; left: 140px; }

        .top-ui { position: absolute; top: 40px; left: 40px; pointer-events: none; z-index: 1000; }
        .top-ui h1 { font-family: 'Outfit'; font-size: 3rem; margin: 0; letter-spacing: -2px; }

        /* --- MODAL INFO --- */
        #modal-confirm { 
            top: 0; left: var(--sidebar-width); 
            width: calc(100vw - var(--sidebar-width)); height: 100vh;
            background: rgba(3, 7, 18, 0.9); backdrop-filter: blur(20px); 
            display: none; align-items: center; justify-content: center; z-index: 9999; 
        }
        
        .booking-card { 
            background: #0f172a; border: 1px solid var(--border); border-radius: 40px; 
            width: 1000px; display: grid; grid-template-columns: 380px 1fr; 
            overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.5);
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes modalPop { from { transform: translateY(40px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }

        .booking-preview { 
            background: linear-gradient(135deg, #1e1b4b 0%, #030712 100%); 
            padding: 3.5rem; display: flex; flex-direction: column;
            border-right: 1px solid var(--border);
        }
        .booking-form { padding: 4rem; background: #0b1120; }

        .info-pill { background: rgba(255,255,255,0.03); border: 1px solid var(--border); padding: 12px 15px; border-radius: 12px; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; }
        .info-pill i { color: var(--accent); }

        .custom-input { 
            width: 100%; background: rgba(255,255,255,0.02); border: 1px solid var(--border); 
            padding: 15px; border-radius: 12px; color: white; outline: none; margin-top: 8px; font-family: 'Inter';
        }

        .btn-confirm { width: 100%; padding: 20px; background: var(--grad); color: white; border: none; border-radius: 15px; font-weight: 800; font-family: 'Outfit'; font-size: 1.1rem; cursor: pointer; transition: 0.3s; margin-top: 2rem; }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3); }

    </style>
</head>
<body>

    
    <?php include '../includes/sidebar_user.php'; ?>

    <main id="viewport">
        <div class="top-ui">
            <h1><?= htmlspecialchars($salaNombre) ?></h1>
            <p style="color: var(--text-muted); font-weight: 600;">Navegá el mapa y tocá un puesto disponible.</p>
        </div>

        <div id="pan-wrapper">
            <div id="scene">
                <div class="grid-floor">
                    
                    <div class="island" style="top: 400px; left: 400px;">
                        <div class="table-surface" 
                             data-name="Mesa Alpha-01" 
                             data-desc="Estación colaborativa con hubs de carga integrados."
                             data-type="Área Grupal"
                             data-spec="6 Enchufes + RJ45">Isla de Trabajo A</div>
                        
                        <div class="chair free p-t1" data-name="Puesto A-01" data-desc="Asiento ergonómico con monitor dual 4K." data-type="Escritorio Individual" data-spec="2 Monitores 27'"></div>
                        <div class="chair occupied p-t2"></div>
                        <div class="chair free p-b1" data-name="Puesto A-03" data-desc="Puesto cómodo en zona de silencio absoluto." data-type="Escritorio Fijo" data-spec="Carga Inalámbrica"></div>
                        <div class="chair free p-b2" data-name="Puesto A-04" data-desc="Ideal para deep work, cerca de la ventana." data-type="Escritorio Fijo" data-spec="Luz Natural"></div>
                    </div>

                    <div class="island" style="top: 400px; left: 900px;">
                        <div class="table-surface" data-name="Mesa Beta-02" data-desc="Mesa circular para brainstorming rápido." data-type="Meeting Hub" data-spec="Pizarra Digital">Isla de Trabajo B</div>
                        <div class="chair free p-t1" data-name="Puesto B-01" data-desc="Puesto dinámico." data-type="Flexible" data-spec="Móvil"></div>
                        <div class="chair free p-t2" data-name="Puesto B-02" data-desc="Puesto dinámico." data-type="Flexible" data-spec="Móvil"></div>
                        <div class="chair free p-b1" data-name="Puesto B-03" data-desc="Puesto dinámico." data-type="Flexible" data-spec="Móvil"></div>
                        <div class="chair occupied p-b2"></div>
                    </div>

                </div>
            </div>
        </div>

        <div id="modal-confirm">
            <div class="booking-card">
                <div class="booking-preview">
                    <div style="width:55px; height:55px; background:var(--primary); border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 id="display-name" style="font-family:'Outfit'; font-size:2.4rem; margin-bottom:5px;">--</h2>
                    <span id="display-type" style="color:var(--accent); font-weight:800; text-transform:uppercase; font-size:0.75rem; letter-spacing:1px; margin-bottom:1.5rem; display:block;">--</span>
                    <p id="display-desc" style="color:var(--text-muted); font-size:0.95rem; line-height:1.6; margin-bottom:2rem;">--</p>
                    <div class="info-pill"><i class="fas fa-microchip"></i> <span id="display-spec">--</span></div>
                    <div class="info-pill"><i class="fas fa-wifi"></i> Conexión 1Gbps Simétrica</div>
                    <div style="margin-top:auto; padding:22px; background:rgba(255,255,255,0.02); border-radius:22px; border:1px solid var(--border);">
                        <span style="font-size:0.7rem; font-weight:800; color:var(--accent); text-transform:uppercase;">Créditos / Hora</span>
                        <h3 id="costo-val" style="font-family:'Outfit'; font-size:1.8rem; margin-top:5px; color: white;">2.0</h3>
                    </div>
                </div>

                <div class="booking-form">
                    <h3 style="font-family:'Outfit'; font-size:1.5rem; margin-bottom:1.5rem;">Detalles de la Jornada</h3>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="font-size:0.7rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Fecha</label>
                            <input type="date" class="custom-input" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div>
                            <label style="font-size:0.7rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Duración</label>
                            <select class="custom-input" id="duration" onchange="updatePrice()">
                                <option value="1">1 Hora</option>
                                <option value="2">2 Horas</option>
                                <option value="4" selected>4 Horas</option>
                                <option value="8">Full Day</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="finish()" class="btn-confirm">RESERVAR ESPACIO</button>
                    <button onclick="closeModal()" style="width:100%; background:none; border:none; color:var(--text-muted); margin-top:20px; cursor:pointer; font-weight:700; font-size:0.9rem;">Volver al plano</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        const panWrapper = document.getElementById('pan-wrapper');
        const viewport = document.getElementById('viewport');
        const body = document.body;
        const topUI = document.querySelector('.top-ui');

        let isDragging = false;
        let x = -200, y = -150; 
        let startX, startY;
        let mStartX, mStartY;
        const moveThreshold = 8;

        panWrapper.style.transform = `translate3d(${x}px, ${y}px, 0)`;

        viewport.addEventListener('mousedown', (e) => {
            isDragging = true;
            mStartX = e.clientX;
            mStartY = e.clientY;
            startX = e.clientX - x;
            startY = e.clientY - y;
            body.classList.add('dragging-map');
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            x = e.clientX - startX;
            y = e.clientY - startY;
            panWrapper.style.transform = `translate3d(${x}px, ${y}px, 0)`;
        });

        window.addEventListener('mouseup', (e) => {
            if (!isDragging) return;
            body.classList.remove('dragging-map');
            isDragging = false;

            const deltaX = Math.abs(e.clientX - mStartX);
            const deltaY = Math.abs(e.clientY - mStartY);

            if (deltaX < moveThreshold && deltaY < moveThreshold) {
                const target = e.target.closest('[data-name]');
                if (target && !target.classList.contains('occupied')) {
                    openBooking(
                        target.getAttribute('data-name'),
                        target.getAttribute('data-desc'),
                        target.getAttribute('data-type'),
                        target.getAttribute('data-spec')
                    );
                }
            }
        });

        function openBooking(name, desc, type, spec) {
            document.getElementById('display-name').innerText = name;
            document.getElementById('display-desc').innerText = desc;
            document.getElementById('display-type').innerText = type;
            document.getElementById('display-spec').innerText = spec;
            
            // CORRECCIÓN: Ocultar elementos de fondo
            topUI.style.display = 'none';
            panWrapper.style.display = 'none';
            
            document.getElementById('modal-confirm').style.display = 'flex';
            updatePrice();
        }

        function closeModal() { 
            // Restaurar visibilidad de fondo
            topUI.style.display = 'block';
            panWrapper.style.display = 'flex';
            
            document.getElementById('modal-confirm').style.display = 'none'; 
        }

        function updatePrice() {
            const h = document.getElementById('duration').value;
            document.getElementById('costo-val').innerText = (h * 2.5).toFixed(1);
        }
        
        function finish() { 
            alert("¡Excelente elección Tomás! Tu reserva ha sido confirmada."); 
            window.location.href = 'my_reserve.php'; 
        }
    </script>
</body>
</html>