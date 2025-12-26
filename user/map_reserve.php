<?php 
    $salaNombre = isset($_GET['sala']) ? $_GET['sala'] : 'Sector Alpha-01';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot | Reserva de Puesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary: #6366f1; 
            --accent: #00f2ff;
            --bg: #030712; 
            --surface: #0f172a;
            --border: rgba(255, 255, 255, 0.1); 
            --free: #10b981; 
            --occupied: #ef4444; 
            --text-muted: #94a3b8;
            --sidebar-width: 290px;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: white; margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        /* Área de trabajo al lado del sidebar */
        main { 
            flex: 1; 
            margin-left: var(--sidebar-width); 
            position: relative; 
            background: radial-gradient(circle at center, #111827 0%, #030712 100%); 
            perspective: 2000px; 
            overflow: hidden;
        }
        
        #pan-wrapper { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transform-style: preserve-3d; cursor: grab; }
        #pan-wrapper:active { cursor: grabbing; }

        #scene { width: 1800px; height: 1400px; transform-style: preserve-3d; transform: rotateX(55deg) rotateZ(-30deg); transition: transform 0.1s linear; }
        .grid-floor { width: 100%; height: 100%; background-image: linear-gradient(var(--border) 1px, transparent 1px), linear-gradient(90deg, var(--border) 1px, transparent 1px); background-size: 60px 60px; border: 2px solid var(--border); position: relative; transform-style: preserve-3d; }

        /* Estructura de Islas y Sillas */
        .island { position: absolute; width: 220px; height: 150px; transform-style: preserve-3d; }
        
        .table-surface { 
            position: absolute; width: 100%; height: 100%; 
            background: #1e293b; border: 2.5px solid var(--primary); 
            transform: translateZ(40px); border-radius: 15px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 0.8rem; font-weight: 800; cursor: pointer; 
            z-index: 5; transition: 0.3s;
        }
        .table-surface:hover { background: var(--primary); box-shadow: 0 0 30px var(--primary); }

        .chair { 
            position: absolute; width: 40px; height: 40px; border-radius: 12px; 
            transform: translateZ(25px); border: 2px solid rgba(255,255,255,0.2); 
            cursor: pointer; z-index: 50; transition: 0.3s; 
        }
        .chair.free { background: var(--free); box-shadow: 0 0 20px var(--free); }
        .chair.occupied { background: var(--occupied); box-shadow: 0 0 20px var(--occupied); cursor: not-allowed; opacity: 0.6; }
        .chair:hover.free { border-color: white; box-shadow: 0 0 30px white; }

        .p-t1 { top: -50px; left: 35px; } .p-t2 { top: -50px; left: 145px; }
        .p-b1 { bottom: -50px; left: 35px; } .p-b2 { bottom: -50px; left: 145px; }

        .top-ui { position: absolute; top: 40px; left: 40px; pointer-events: none; z-index: 100; }
        .top-ui h1 { font-family: 'Outfit'; font-size: 2.8rem; margin: 0; letter-spacing: -1.5px; }

        /* --- MODAL CENTRADO EN EL ÁREA DE TRABAJO --- */
        #modal-confirm { 
            position: fixed; 
            top: 0;
            left: var(--sidebar-width); 
            width: calc(100vw - var(--sidebar-width)); 
            height: 100vh;
            background: rgba(3, 7, 18, 0.6); 
            backdrop-filter: blur(25px); 
            display: none; 
            align-items: center; 
            justify-content: center; 
            z-index: 9999; 
        }
        
        .booking-card { 
            background: #0f172a; border: 1px solid var(--border); border-radius: 45px; 
            width: 1000px; max-width: 95%; display: grid; grid-template-columns: 380px 1fr; 
            overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.8);
            animation: modalPop 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes modalPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        /* Estilos del Formulario */
        .booking-preview { 
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); 
            padding: 4rem 3rem; display: flex; flex-direction: column; justify-content: center;
            border-right: 1px solid var(--border);
        }
        .preview-icon { width: 65px; height: 65px; background: var(--primary); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 2rem; }
        .booking-preview h2 { font-family: 'Outfit'; font-size: 2.8rem; margin: 0; }
        
        .cost-box { margin-top: 3rem; background: rgba(255,255,255,0.05); padding: 25px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); }
        .cost-box h3 { font-size: 2.2rem; margin: 10px 0 0 0; font-family: 'Outfit'; }

        .booking-form { padding: 4rem; background: #0f172a; max-height: 90vh; overflow-y: auto; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; }
        
        .custom-input { 
            width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--border); 
            padding: 16px; border-radius: 14px; color: white; outline: none; font-weight: 600; 
            font-family: 'Inter'; box-sizing: border-box; transition: 0.3s;
        }
        .custom-input:focus { border-color: var(--primary); background: rgba(99, 102, 241, 0.05); }

        .notebook-card { 
            display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.03); 
            padding: 20px; border-radius: 18px; border: 1px solid var(--border); cursor: pointer; 
        }
        .notebook-card input { width: 22px; height: 22px; accent-color: var(--primary); }

        .btn-confirm { 
            width: 100%; padding: 22px; background: var(--primary); color: white; border: none; 
            border-radius: 20px; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.3s; 
            margin-top: 25px;
        }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4); }
    </style>
</head>
<body>
    <?php include '../includes/sidebar_user.php'; ?>
    
    <main id="viewport">
        <div class="top-ui">
            <h1><?= htmlspecialchars($salaNombre) ?></h1>
            <p style="color: var(--text-muted);">Tocá un puesto verde para configurarlo.</p>
        </div>

        <div id="pan-wrapper">
            <div id="scene">
                <div class="grid-floor">
                    <div class="island" style="top: 350px; left: 400px;">
                        <div class="table-surface" onclick="handleItemClick('Mesa Alpha-01')">Mesa de Trabajo</div>
                        <div class="chair free p-t1" onclick="handleItemClick('Puesto 01')"></div>
                        <div class="chair occupied p-t2"></div>
                        <div class="chair free p-b1" onclick="handleItemClick('Puesto 03')"></div>
                        <div class="chair free p-b2" onclick="handleItemClick('Puesto 04')"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-confirm">
            <div class="booking-card">
                <div class="booking-preview">
                    <div class="preview-icon"><i class="fas fa-fingerprint"></i></div>
                    <h2 id="display-item">--</h2>
                    <p>Iniciando proceso de registro para tu jornada laboral.</p>
                    <div class="cost-box">
                        <span style="font-size: 0.7rem; color: var(--accent); font-weight: 800;">Créditos Requeridos</span>
                        <h3 id="costo-val">8 Créditos</h3>
                    </div>
                </div>

                <div class="booking-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="custom-input" placeholder="Tu nombre">
                        </div>
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" class="custom-input" placeholder="Tu apellido">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 30px;">
                        <label>DNI / Documento</label>
                        <input type="number" class="custom-input" placeholder="Sin puntos ni espacios">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="date" class="custom-input" value="2025-12-25">
                        </div>
                        <div class="form-group">
                            <label>Duración</label>
                            <select class="custom-input" id="duration" onchange="updatePrice()">
                                <option value="1">1 Hora</option>
                                <option value="2">2 Horas</option>
                                <option value="4" selected>4 Horas</option>
                                <option value="8">Jornada Completa</option>
                            </select>
                        </div>
                    </div>

                    <label class="notebook-card">
                        <div style="display:flex; align-items:center; gap:15px;">
                            <i class="fas fa-laptop" style="color:var(--primary);"></i>
                            <span style="font-weight:600;">¿Traés tu propia Notebook?</span>
                        </div>
                        <input type="checkbox" id="checkNote">
                    </label>

                    <button onclick="finish()" class="btn-confirm">CONFIRMAR Y REGISTRAR</button>
                    <button onclick="closeModal()" style="width:100%; background:none; border:none; color:var(--text-muted); cursor:pointer; margin-top:20px; font-weight:700;">Volver al plano</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        const panWrapper = document.getElementById('pan-wrapper');
        let isDragging = false, startX, startY, x = 0, y = 0;
        let dragStartX, dragStartY;

        // --- LÓGICA DE MOVIMIENTO DEL MAPA ---
        document.getElementById('viewport').addEventListener('mousedown', (e) => {
            isDragging = true;
            dragStartX = e.clientX;
            dragStartY = e.clientY;
            startX = e.clientX - x;
            startY = e.clientY - y;
            panWrapper.style.transition = 'none';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            x = e.clientX - startX;
            y = e.clientY - startY;
            panWrapper.style.transform = `translate3d(${x}px, ${y}px, 0)`;
        });

        window.addEventListener('mouseup', () => { isDragging = false; });

        // --- LÓGICA DE CLIC (CON UMBRAL DE MOVIMIENTO) ---
        function handleItemClick(name) {
            const moveThreshold = 6; // Máximo movimiento permitido para que sea un clic
            const deltaX = Math.abs(event.clientX - dragStartX);
            const deltaY = Math.abs(event.clientY - dragStartY);

            if (deltaX < moveThreshold && deltaY < moveThreshold) {
                openBooking(name);
            }
        }

        function openBooking(name) {
            document.getElementById('display-item').innerText = name;
            document.getElementById('modal-confirm').style.display = 'flex';
            updatePrice();
        }

        function updatePrice() {
            const h = document.getElementById('duration').value;
            document.getElementById('costo-val').innerText = (h * 2) + " Créditos";
        }

        function closeModal() { document.getElementById('modal-confirm').style.display = 'none'; }
        
        function finish() { 
            alert("¡Perfecto Tomás! Tu reserva ha sido registrada."); 
            window.location.href = 'my_reserve.php'; 
        }
    </script>
</body>
</html>