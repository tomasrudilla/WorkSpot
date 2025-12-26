<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot Admin | Enterprise Control Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg: #030712;
            --accent: #6366f1;
            --surface: #0f172a;
            --border: rgba(255, 255, 255, 0.08);
            --free: #10b981;
            --occupied: #ef4444;
            --text-muted: #94a3b8;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: #fff; margin: 0; display: flex; height: 100vh; overflow: hidden; }

        /* --- LAYOUT: SIDEBAR SIEMPRE ARRIBA --- */
        .app-container { display: flex; width: 100%; height: 100vh; }
        
        /* Aseguramos que el sidebar no se oculte nunca y esté a la izquierda */
        aside.sidebar { 
            z-index: 2000 !important; 
            position: relative; 
            flex-shrink: 0; 
            height: 100vh;
        }

        main { flex: 1; position: relative; overflow: hidden; background: #000; display: flex; flex-direction: column; }

        /* --- VISTA 1: PLANO (MIRO STYLE) --- */
        #map-view {
            flex: 1; display: flex; align-items: center; justify-content: center;
            background: radial-gradient(circle at center, #111827 0%, #030712 100%);
            perspective: 2500px; cursor: grab; transition: opacity 0.5s ease;
        }
        #map-view.hidden { opacity: 0; pointer-events: none; }

        #pan-wrapper { 
            width: 100%; height: 100%; 
            display: flex; align-items: center; justify-content: center; 
            transform-style: preserve-3d; 
            pointer-events: auto;
        }

        #scene { 
            width: 1800px; height: 1400px; 
            transform-style: preserve-3d; 
            transform: rotateX(55deg) rotateZ(-30deg); 
            transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1); 
        }

        .grid-floor { 
            width: 100%; height: 100%; 
            background-image: linear-gradient(var(--border) 1px, transparent 1px), linear-gradient(90deg, var(--border) 1px, transparent 1px); 
            background-size: 60px 60px; border: 2px solid var(--border); 
            position: relative; transform-style: preserve-3d; 
        }

        /* --- OBJETOS INTERACTIVOS --- */
        .island { position: absolute; width: 200px; height: 140px; transform-style: preserve-3d; }
        
        .table-surface { 
            position: absolute; width: 100%; height: 100%; 
            background: #1e293b; border: 2px solid var(--accent); 
            transform: translateZ(40px); border-radius: 15px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 0.8rem; font-weight: 800; color: #fff; 
            cursor: pointer; pointer-events: auto; transition: 0.3s;
            z-index: 5;
        }
        .table-surface:hover { background: var(--accent); transform: translateZ(50px); }

        .chair-dot { 
            position: absolute; width: 35px; height: 35px; border-radius: 10px; 
            transform: translateZ(25px); border: 2px solid rgba(255,255,255,0.2); 
            cursor: pointer; pointer-events: auto; transition: 0.3s; 
            z-index: 20; /* Arriba de la mesa */
        }
        .chair-dot.occupied { background: var(--occupied); box-shadow: 0 0 20px var(--occupied); }
        .chair-dot.free { background: var(--free); box-shadow: 0 0 20px var(--free); }
        .chair-dot:hover { transform: translateZ(70px) scale(1.2); border-color: #fff; }

        .p-t1 { top: -45px; left: 35px; } .p-t2 { top: -45px; left: 130px; }
        .p-b1 { bottom: -45px; left: 35px; } .p-b2 { bottom: -45px; left: 130px; }

        /* --- OVERLAYS DE DATOS --- */
        .overlay-view { 
            position: absolute; inset: 0; background: var(--bg); 
            display: none; padding: 4rem; overflow-y: auto; z-index: 3000; 
            animation: slideUp 0.4s ease-out;
        }
        @keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .back-btn { background: #1f2937; border: 1px solid var(--border); color: #fff; padding: 12px 25px; border-radius: 15px; cursor: pointer; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; margin-bottom: 3rem; }

        .ent-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .ent-card { background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 30px; padding: 2rem; }
        .ent-card h3 { font-family: 'Outfit'; margin-top: 0; color: var(--accent); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }

        .data-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .data-row span { color: var(--text-muted); }
        .data-row b { color: #fff; }
    </style>
</head>
<body>

    <div class="app-container">
        <?php include '../includes/sidebar_admin.php'; ?>

        <main>
            <section id="map-view">
                <div style="position: absolute; top: 40px; left: 40px; z-index: 100; pointer-events: none;">
                    <h1 style="font-family: 'Outfit'; font-size: 2.2rem; margin:0;">Centro de Comando</h1>
                    <p style="color: var(--text-muted);">Toca la madera para la Mesa | Toca los LEDs para el Usuario</p>
                </div>

                <div id="pan-wrapper">
                    <div id="scene">
                        <div class="grid-floor">
                            
                            <div class="island" style="top: 250px; left: 250px;">
                                <div class="table-surface" onclick="openMesa('Alpha-01', 'Globant S.A.', 'Cuenta Corporativa')">ALPHA-01</div>
                                <div class="chair-dot occupied p-t1" onclick="openUser('TG', 'Tomás González', 'Sr. Developer', 'Globant')"></div>
                                <div class="chair-dot occupied p-t2" onclick="openUser('MS', 'Micaela Sanchez', 'UX Designer', 'Globant')"></div>
                                <div class="chair-dot free p-b1" onclick="openUser('?', 'Disponible', 'N/A', '-')"></div>
                                <div class="chair-dot occupied p-b2" onclick="openUser('LF', 'Lucas Ferro', 'Backend', 'Globant')"></div>
                            </div>

                            <div class="island" style="top: 250px; left: 700px;">
                                <div class="table-surface" onclick="openMesa('Beta-02', 'Networking Hub', 'Espacio Mixto')">BETA-02</div>
                                <div class="chair-dot occupied p-t1" onclick="openUser('ER', 'Enzo Rossi', 'Freelancer', 'Individual')"></div>
                                <div class="chair-dot free p-t2" onclick="openUser('?', 'Disponible', 'N/A', '-')"></div>
                                <div class="chair-dot occupied p-b1" onclick="openUser('SG', 'Sofia Gomez', 'Copywriter', 'Agency X')"></div>
                                <div class="chair-dot free p-b2" onclick="openUser('?', 'Disponible', 'N/A', '-')"></div>
                            </div>

                            <div class="island" style="top: 600px; left: 475px;">
                                <div class="table-surface" onclick="openMesa('Gamma-03', 'Sala Scrum', 'Reuniones')">GAMMA-03</div>
                                <div class="chair-dot occupied p-t1" onclick="openUser('MR', 'Marcos Ruiz', 'Project Manager', 'WorkSpot')"></div>
                                <div class="chair-dot occupied p-t2" onclick="openUser('AL', 'Ana Lis', 'Product Owner', 'WorkSpot')"></div>
                                <div class="chair-dot free p-b1" onclick="openUser('?', 'Disponible', 'N/A', '-')"></div>
                                <div class="chair-dot free p-b2" onclick="openUser('?', 'Disponible', 'N/A', '-')"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section id="mesa-overlay" class="overlay-view">
                <button class="back-btn" onclick="closeAll()"><i class="fas fa-arrow-left"></i> VOLVER AL PLANO</button>
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
                    <div>
                        <h1 id="m-name" style="font-family: 'Outfit'; font-size: 4rem; margin: 0;">--</h1>
                        <p id="m-comp" style="font-size: 1.5rem; color: var(--accent); margin: 0; font-weight: 700;">--</p>
                    </div>
                    <span id="m-type" style="padding: 10px 25px; background: var(--accent); border-radius: 12px; font-weight: 800;">--</span>
                </div>

                <div class="ent-grid" id="mesa-cards-container">
                    <div class="ent-card">
                        <h3><i class="fas fa-laptop"></i> Equipamiento Técnico</h3>
                        <div id="m-hardware-list">
                            <div class="data-row"><span>Monitores</span><b>2x Odyssey G7 28"</b></div>
                            <div class="data-row"><span>Hub USB</span><b>Thunderbolt 4 Station</b></div>
                            <div class="data-row"><span>Periféricos</span><b>Keychron K2 + MX Master</b></div>
                            <div class="data-row"><span>Sillas</span><b>ErgoChair Pro v3</b></div>
                        </div>
                    </div>
                    <div class="ent-card">
                        <h3><i class="fas fa-history"></i> Gestión de Fechas</h3>
                        <div id="m-dates-list">
                            <div class="data-row"><span>Alquiler Desde</span><b>01/12/2025</b></div>
                            <div class="data-row"><span>Próximo Pago</span><b>01/01/2026</b></div>
                            <div class="data-row"><span>Horas de Uso Mes</span><b>184h</b></div>
                            <div class="data-row" style="color: var(--free);"><span>Mantenimiento</span><b>Al día</b></div>
                        </div>
                    </div>
                    <div class="ent-card">
                        <h3><i class="fas fa-wifi"></i> Conectividad</h3>
                        <div class="data-row"><span>Red</span><b>WS-Enterprise-A</b></div>
                        <div class="data-row"><span>IP Local</span><b>10.0.4.122</b></div>
                        <div class="data-row"><span>Uptime</span><b>99.9%</b></div>
                        <div class="data-row"><span>Seguridad</span><b style="color: var(--free);">Firewall Activo</b></div>
                    </div>
                </div>
            </section>

            <section id="user-overlay" class="overlay-view">
                <button class="back-btn" onclick="closeAll()"><i class="fas fa-arrow-left"></i> VOLVER AL PLANO</button>
                <div style="display: grid; grid-template-columns: 350px 1fr; gap: 4rem;">
                    <div style="text-align: center; background: rgba(255,255,255,0.02); padding: 3rem; border-radius: 40px; border: 1px solid var(--border);">
                        <div id="u-ava" style="width: 140px; height: 140px; background: var(--accent); border-radius: 40px; margin: 0 auto 2rem; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; font-weight: 800; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">--</div>
                        <h1 id="u-nom" style="font-family: 'Outfit'; margin: 0; font-size: 1.8rem;">--</h1>
                        <p id="u-rol" style="color: var(--accent); font-weight: 700;">--</p>
                        <hr style="border: 0; border-top: 1px solid var(--border); margin: 2.5rem 0;">
                        <button style="width:100%; padding: 15px; background: var(--occupied); border:none; border-radius: 12px; color:white; font-weight:700; cursor:pointer;">EXPULSAR DE SESIÓN</button>
                    </div>
                    <div>
                        <h2 style="font-family: 'Outfit'; font-size: 2.5rem; margin-top: 0;">Historial y Activos</h2>
                        <div class="ent-grid" style="grid-template-columns: 1fr 1fr;">
                            <div class="ent-card">
                                <h3>Vínculo Laboral</h3>
                                <div class="data-row"><span>Empresa</span><b id="u-com">--</b></div>
                                <div class="data-row"><span>Contrato</span><b>Anual Corp</b></div>
                                <div class="data-row"><span>Antigüedad</span><b>14 meses</b></div>
                            </div>
                            <div class="ent-card">
                                <h3>Hardware en Posesión</h3>
                                <div class="data-row"><span>Notebook</span><b>MBP M3 Max 16"</b></div>
                                <div class="data-row"><span>Auriculares</span><b>Sony WH-1000XM5</b></div>
                                <div class="data-row"><span>ID Badge</span><b>#WS-9922</b></div>
                            </div>
                        </div>
                        <div style="margin-top: 2rem; background: rgba(16,185,129,0.05); padding: 2rem; border-radius: 25px; border-left: 5px solid var(--free);">
                            <h4 style="margin: 0 0 10px 0;">Notas de Soporte</h4>
                            <p style="margin:0; font-size: 0.9rem; color: var(--text-muted);">Usuario reportó problemas con el aire acondicionado del sector B. Se le asignó prioridad de atención. No registra deudas en la cafetería.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const panWrapper = document.getElementById('pan-wrapper');
        const mapView = document.getElementById('map-view');
        const mesaOverlay = document.getElementById('mesa-overlay');
        const userOverlay = document.getElementById('user-overlay');

        let isDragging = false, moved = false, startX, startY, x = 0, y = 0;

        mapView.addEventListener('mousedown', (e) => {
            if (e.target.closest('.chair-dot') || e.target.closest('.table-surface')) {
                isDragging = false;
                return;
            }
            isDragging = true; moved = false;
            startX = e.clientX - x; startY = e.clientY - y;
            panWrapper.style.transition = 'none';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            moved = true; 
            x = e.clientX - startX;
            y = e.clientY - startY;
            panWrapper.style.transform = `translate3d(${x}px, ${y}px, 0)`;
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
        });

        function openMesa(name, company, type) {
            if (moved) return;
            mapView.classList.add('hidden');
            setTimeout(() => {
                mapView.style.display = 'none';
                mesaOverlay.style.display = 'block';
                document.getElementById('m-name').innerText = name;
                document.getElementById('m-comp').innerText = company;
                document.getElementById('m-type').innerText = type.toUpperCase();

                // --- INFO EXTRA PARA GAMMA-03 ---
                if(name === 'Gamma-03') {
                    // Cambiamos el hardware por algo más de "Sala de Juntas"
                    document.getElementById('m-hardware-list').innerHTML = `
                        <div class="data-row"><span>Pantalla Principal</span><b>TV Sony 75" 4K</b></div>
                        <div class="data-row"><span>Audio/Video</span><b>Logitech Rally Bar</b></div>
                        <div class="data-row"><span>Control</span><b>iPad Air 5th Gen</b></div>
                        <div class="data-row"><span>Pizarra</span><b>Muro de Cristal Magnético</b></div>
                        <div class="data-row"><span>Confort</span><b>A/A Independiente</b></div>
                    `;
                    // Cambiamos las fechas por integrantes y periodos
                    document.getElementById('m-dates-list').innerHTML = `
                        <div class="data-row"><span>Marcos Ruiz (PM)</span><b>01/12 al 30/12</b></div>
                        <div class="data-row"><span>Ana Lis (PO)</span><b>01/12 al 30/12</b></div>
                        <div class="data-row"><span>Silla 3</span><b style="color:var(--free)">LIBRE</b></div>
                        <div class="data-row"><span>Silla 4</span><b style="color:var(--free)">LIBRE</b></div>
                        <div class="data-row" style="margin-top:10px; padding-top:10px; border-top:1px dashed var(--border)">
                            <span>Contrato</span><b style="color:var(--accent)">REJUNTE INTERNO</b>
                        </div>
                    `;
                } else {
                    // Resetear a valores por defecto para otras mesas
                    document.getElementById('m-hardware-list').innerHTML = `
                        <div class="data-row"><span>Monitores</span><b>2x Odyssey G7 28"</b></div>
                        <div class="data-row"><span>Hub USB</span><b>Thunderbolt 4 Station</b></div>
                        <div class="data-row"><span>Periféricos</span><b>Keychron K2 + MX Master</b></div>
                        <div class="data-row"><span>Sillas</span><b>ErgoChair Pro v3</b></div>
                    `;
                    document.getElementById('m-dates-list').innerHTML = `
                        <div class="data-row"><span>Alquiler Desde</span><b>01/12/2025</b></div>
                        <div class="data-row"><span>Próximo Pago</span><b>01/01/2026</b></div>
                        <div class="data-row"><span>Horas de Uso Mes</span><b>184h</b></div>
                        <div class="data-row" style="color: var(--free);"><span>Mantenimiento</span><b>Al día</b></div>
                    `;
                }
            }, 500);
        }

        function openUser(ava, nom, rol, com) {
            if (moved) return;
            mapView.classList.add('hidden');
            setTimeout(() => {
                mapView.style.display = 'none';
                userOverlay.style.display = 'block';
                document.getElementById('u-ava').innerText = ava;
                document.getElementById('u-nom').innerText = nom;
                document.getElementById('u-rol').innerText = rol;
                document.getElementById('u-com').innerText = com;
            }, 500);
        }

        function closeAll() {
            mesaOverlay.style.display = 'none';
            userOverlay.style.display = 'none';
            mapView.style.display = 'flex';
            setTimeout(() => mapView.classList.remove('hidden'), 50);
        }
    </script>
</body>
</html>