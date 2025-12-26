<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Reservar Espacio</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #0ea5e9;
            --bg: #030712;
            --surface: rgba(17, 24, 39, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
            --glow: rgba(99, 102, 241, 0.3);
            --success: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Background Glow */
        .aurora {
            position: fixed; top: -100px; right: -100px;
            width: 50vw; height: 50vw; background: var(--gradient);
            filter: blur(150px); opacity: 0.1; z-index: -1;
        }

        main {
            flex: 1;
            margin-left: 290px; /* Ancho del sidebar */
            padding: 4rem;
        }

        /* --- Header Section --- */
        .header-section {
            margin-bottom: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .header-section h1 {
            font-family: 'Outfit';
            font-size: 3rem;
            letter-spacing: -1.5px;
            margin-bottom: 0.5rem;
        }

        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 3rem;
        }

        .filter-btn {
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .filter-btn.active, .filter-btn:hover {
            border-color: var(--primary);
            color: white;
            background: rgba(99, 102, 241, 0.1);
        }

        /* --- Grid de Cards --- */
        .space-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2.5rem;
        }

        .space-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 35px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(20px);
            position: relative;
        }

        .space-card:hover {
            transform: translateY(-15px);
            border-color: var(--primary);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 20px rgba(99, 102, 241, 0.2);
        }

        /* Imagen y Badges */
        .card-img-container {
            height: 240px;
            position: relative;
            overflow: hidden;
        }

        .card-img {
            width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s ease;
        }

        .space-card:hover .card-img {
            transform: scale(1.1);
        }

        .badge-availability {
            position: absolute; top: 20px; left: 20px;
            background: rgba(3, 7, 18, 0.6);
            backdrop-filter: blur(10px);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--success);
            border: 1px solid var(--success);
            display: flex; align-items: center; gap: 6px;
        }

        .badge-floor {
            position: absolute; top: 20px; right: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
            border: 1px solid var(--border);
        }

        /* Cuerpo de la Card */
        .card-body { padding: 2.2rem; }

        .card-category {
            color: var(--primary);
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 0.5rem;
        }

        .card-body h3 {
            font-family: 'Outfit';
            font-size: 1.6rem;
            margin-bottom: 0.8rem;
        }

        .card-description {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            height: 45px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Amenities */
        .amenities {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .amenity-icon {
            color: var(--text-muted);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
        }

        .amenity-icon i { color: var(--secondary); }

        /* Footer y Botón */
        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .price-tag { font-family: 'Outfit'; font-size: 1.2rem; font-weight: 800; }
        .price-tag span { font-size: 0.8rem; color: var(--text-muted); font-weight: 400; }

        .btn-reserve {
            flex: 1;
            padding: 16px;
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 18px;
            font-family: 'Outfit';
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }

        .btn-reserve:hover {
            box-shadow: 0 10px 20px var(--glow);
            transform: scale(1.02);
        }

    </style>
</head>
<body>

    <div class="aurora"></div>
    
    <?php include '../includes/sidebar_user.php'; ?>

    <main>
        <div class="header-section">
            <div>
                <h1>Encontrá tu lugar 🚀</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem;">Seleccioná el entorno ideal para tus objetivos de hoy.</p>
            </div>
            <div class="filters">
                <button class="filter-btn active">Todos</button>
                <button class="filter-btn">Escritorios</button>
                <button class="filter-btn">Salas de Reunión</button>
                <button class="filter-btn">Zonas Relax</button>
            </div>
        </div>

        <div class="space-grid">
            
            <div class="space-card" onclick="location.href='map_reserve.php?sala=Isla Alpha-01'">
                <div class="card-img-container">
                    <div class="badge-availability"><i class="fas fa-circle"></i> 12 Libres</div>
                    <div class="badge-floor">Piso 2</div>
                    <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800');"></div>
                </div>
                <div class="card-body">
                    <span class="card-category">Coworking Space</span>
                    <h3>Isla Alpha-01</h3>
                    <p class="card-description">Zona de alta concentración con escritorios regulables y monitores 4K de 27".</p>
                    
                    <div class="amenities">
                        <div class="amenity-icon"><i class="fas fa-wifi"></i> 1Gbps</div>
                        <div class="amenity-icon"><i class="fas fa-plug"></i> USB-C</div>
                        <div class="amenity-icon"><i class="fas fa-users"></i> 1 Persona</div>
                    </div>

                    <div class="card-footer">
                        <button class="btn-reserve">
                            Ver Plano 3D <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-card" onclick="location.href='map_reserve.php?sala=Sala Scrum Gamma'">
                <div class="card-img-container">
                    <div class="badge-availability" style="color: var(--secondary); border-color: var(--secondary);"><i class="fas fa-clock"></i> Próxima: 14:00</div>
                    <div class="badge-floor">Piso 4</div>
                    <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=800');"></div>
                </div>
                <div class="card-body">
                    <span class="card-category">Meeting Room</span>
                    <h3>Sala Scrum Gamma</h3>
                    <p class="card-description">Equipada con pizarra de cristal, TV 65" y sistema de videoconferencia Logitech.</p>
                    
                    <div class="amenities">
                        <div class="amenity-icon"><i class="fas fa-tv"></i> Smart TV</div>
                        <div class="amenity-icon"><i class="fas fa-coffee"></i> Café libre</div>
                        <div class="amenity-icon"><i class="fas fa-users"></i> 6 Pers.</div>
                    </div>

                    <div class="card-footer">
                        <button class="btn-reserve">
                            Ver Plano 3D <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-card" onclick="location.href='map_reserve.php?sala=Private Box 04'">
                <div class="card-img-container">
                    <div class="badge-availability"><i class="fas fa-circle"></i> Disponible</div>
                    <div class="badge-floor">Piso 1</div>
                    <div class="card-img" style="background-image: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=800');"></div>
                </div>
                <div class="card-body">
                    <span class="card-category">Private Focus</span>
                    <h3>Private Box 04</h3>
                    <p class="card-description">Cabina insonorizada ideal para llamadas comerciales o deep work sin distracciones.</p>
                    
                    <div class="amenities">
                        <div class="amenity-icon"><i class="fas fa-volume-mute"></i> Silencio</div>
                        <div class="amenity-icon"><i class="fas fa-wind"></i> AC Indiv.</div>
                        <div class="amenity-icon"><i class="fas fa-users"></i> 1 Pers.</div>
                    </div>

                    <div class="card-footer">
                        <button class="btn-reserve">
                            Ver Plano 3D <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Efecto simple para los botones de filtro
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('.filter-btn.active').classList.remove('active');
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>