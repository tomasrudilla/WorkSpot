<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Schedule Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --bg: #030712; 
            --surface: rgba(30, 41, 59, 0.4); 
            --primary: #6366f1; 
            --accent: #00f2ff;
            --text-main: #f8fafc; 
            --text-muted: #94a3b8; 
            --border: rgba(255, 255, 255, 0.08); 
            --success: #10b981;
            --danger: #ef4444;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        main { flex: 1; padding: 2rem 3rem; overflow-y: auto; }

        /* --- Header --- */
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header-flex h1 { font-family: 'Outfit'; font-size: 2rem; margin: 0; letter-spacing: -1px; }

        /* --- Selector de Salones (Cards) --- */
        .room-selector {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .room-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.2rem;
            cursor: pointer;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .room-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: transparent; transition: 0.3s;
        }

        .room-card.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            transform: translateY(-5px);
        }

        .room-card.active::before { background: var(--primary); }

        .room-card i { font-size: 1.5rem; color: var(--primary); margin-bottom: 1rem; display: block; }
        .room-card h3 { margin: 0; font-family: 'Outfit'; font-size: 1.1rem; }
        .room-card span { font-size: 0.8rem; color: var(--text-muted); }

        /* --- Layout de Calendario --- */
        .calendar-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            height: 600px;
        }

        /* Columna Izquierda: Widget Calendario */
        .mini-calendar {
            background: var(--surface);
            border-radius: 24px;
            border: 1px solid var(--border);
            padding: 1.5rem;
        }

        .cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; text-align: center; }
        .cal-day-name { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); }
        .cal-number { 
            padding: 8px; border-radius: 10px; font-size: 0.85rem; cursor: pointer; transition: 0.2s; 
        }
        .cal-number:hover { background: rgba(255,255,255,0.05); }
        .cal-number.active { background: var(--primary); color: white; font-weight: 700; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }

        /* Columna Derecha: Timeline de Horas */
        .timeline-view {
            background: var(--surface);
            border-radius: 24px;
            border: 1px solid var(--border);
            padding: 1.5rem;
            overflow-y: auto;
            position: relative;
        }

        .timeline-header { border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem; }

        .hour-row {
            display: grid;
            grid-template-columns: 80px 1fr;
            height: 60px;
            border-bottom: 1px solid rgba(255,255,255,0.02);
            position: relative;
        }

        .hour-label { font-size: 0.75rem; color: var(--text-muted); padding-top: 10px; }

        /* Bloque de Reserva en el Timeline */
        .booking-block {
            position: absolute;
            left: 100px;
            right: 20px;
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.2) 0%, rgba(99, 102, 241, 0.05) 100%);
            border-left: 4px solid var(--primary);
            border-radius: 12px;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .booking-info h4 { margin: 0; font-size: 0.9rem; font-family: 'Outfit'; }
        .booking-info p { margin: 0; font-size: 0.75rem; color: var(--text-muted); }

        .status-dot { width: 8px; height: 8px; border-radius: 50%; }

        /* Botón de nueva reserva */
        .btn-add {
            background: var(--primary); color: white; border: none; padding: 10px 20px;
            border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;
        }
        .btn-add:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }

    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="header-flex">
            <div>
                <h1>Gestión de Salones</h1>
                <p style="color: var(--text-muted);">Monitorea la disponibilidad y gestiona los horarios.</p>
            </div>
            <button class="btn-add"><i class="fas fa-plus"></i> Nueva Reserva</button>
        </div>

        <div class="room-selector">
            <div class="room-card active">
                <i class="fas fa-rocket"></i>
                <h3>Sala VIP Marte</h3>
                <span>Ocupación: 85%</span>
            </div>
            <div class="room-card">
                <i class="fas fa-microphone"></i>
                <h3>Sala de Podcast</h3>
                <span>Ocupación: 40%</span>
            </div>
            <div class="room-card">
                <i class="fas fa-laptop-code"></i>
                <h3>Zona Coding</h3>
                <span>Ocupación: 100%</span>
            </div>
            <div class="room-card">
                <i class="fas fa-couch"></i>
                <h3>Lounge Creativo</h3>
                <span>Ocupación: 12%</span>
            </div>
        </div>

        <div class="calendar-container">
            <aside class="mini-calendar">
                <div class="cal-header">
                    <span style="font-weight: 800; font-family: 'Outfit';">Diciembre 2025</span>
                    <div>
                        <i class="fas fa-chevron-left" style="cursor:pointer; margin-right: 10px;"></i>
                        <i class="fas fa-chevron-right" style="cursor:pointer;"></i>
                    </div>
                </div>
                <div class="cal-grid">
                    <div class="cal-day-name">L</div><div class="cal-day-name">M</div><div class="cal-day-name">M</div>
                    <div class="cal-day-name">J</div><div class="cal-day-name">V</div><div class="cal-day-name">S</div><div class="cal-day-name">D</div>
                    
                    <div class="cal-number">22</div><div class="cal-number">23</div><div class="cal-number">24</div>
                    <div class="cal-number active">25</div><div class="cal-number">26</div><div class="cal-number">27</div><div class="cal-number">28</div>
                    <div class="cal-number">29</div><div class="cal-number">30</div><div class="cal-number">31</div><div class="cal-number" style="opacity: 0.2;">1</div>
                </div>

                <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                    <h4 style="font-family: 'Outfit'; font-size: 0.9rem; margin-bottom: 1rem;">Próximas en Sala Marte</h4>
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                        <div style="width: 4px; height: 30px; background: var(--accent); border-radius: 4px;"></div>
                        <div>
                            <div style="font-size: 0.8rem; font-weight: 600;">Reunión Directorio</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">Mañana - 10:00 AM</div>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="timeline-view">
                <div class="timeline-header">
                    <h2 style="font-family: 'Outfit'; font-size: 1.2rem; margin: 0;">Disponibilidad: Jueves 25 de Diciembre</h2>
                </div>

                <div class="hour-row"><div class="hour-label">08:00 AM</div></div>
                <div class="hour-row">
                    <div class="hour-label">09:00 AM</div>
                    <div class="booking-block" style="top: 10px; height: 110px; border-color: var(--accent);">
                        <div class="booking-info">
                            <h4>Mark Zuckerberg</h4>
                            <p><i class="far fa-clock"></i> 09:00 - 11:00 AM</p>
                        </div>
                        <div class="status-dot" style="background: var(--accent); box-shadow: 0 0 10px var(--accent);"></div>
                    </div>
                </div>
                <div class="hour-row"><div class="hour-label">10:00 AM</div></div>
                <div class="hour-row"><div class="hour-label">11:00 AM</div></div>
                <div class="hour-row">
                    <div class="hour-label">12:00 PM</div>
                    <div style="width: 100%; border-top: 2px dashed var(--danger); position: relative; top: 10px; opacity: 0.5;">
                        <span style="position: absolute; right: 0; top: -12px; font-size: 0.6rem; color: var(--danger); font-weight: 800;">AHORA</span>
                    </div>
                </div>
                <div class="hour-row">
                    <div class="hour-label">01:00 PM</div>
                    <div class="booking-block" style="top: 20px; height: 180px; border-color: var(--primary);">
                        <div class="booking-info">
                            <h4>Lionel Messi (Privado)</h4>
                            <p><i class="far fa-clock"></i> 01:20 - 04:30 PM</p>
                        </div>
                        <div class="status-dot" style="background: var(--primary); box-shadow: 0 0 10px var(--primary);"></div>
                    </div>
                </div>
                <div class="hour-row"><div class="hour-label">02:00 PM</div></div>
                <div class="hour-row"><div class="hour-label">03:00 PM</div></div>
                <div class="hour-row"><div class="hour-label">04:00 PM</div></div>
                <div class="hour-row"><div class="hour-label">05:00 PM</div></div>
            </section>
        </div>
    </main>

    <script>
        // Lógica simple para simular selección de salones
        const cards = document.querySelectorAll('.room-card');
        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
            });
        });
    </script>
</body>
</html>