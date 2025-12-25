<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot | Enterprise Coworking Solution</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Modo Oscuro (Base) */
            --bg: #030712;
            --surface: rgba(17, 24, 39, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --primary: #6366f1;
            --secondary: #0ea5e9;
            --gradient: linear-gradient(135deg, #6366f1 0%, #0ea5e9 100%);
            --glow: rgba(99, 102, 241, 0.3);
        }

        .light-mode {
            --bg: #f3f4f6;
            --surface: rgba(255, 255, 255, 0.8);
            --border: rgba(0, 0, 0, 0.1);
            --text-main: #111827;
            --text-muted: #4b5563;
            --glow: rgba(99, 102, 241, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; transition: background-color 0.4s, color 0.4s, border 0.4s, transform 0.3s ease; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        h1, h2, h3, .logo { font-family: 'Outfit', sans-serif; }

        /* --- Aurora Background --- */
        .aurora {
            position: absolute; top: -100px; left: 50%; transform: translateX(-50%);
            width: 80vw; height: 500px; background: var(--gradient);
            filter: blur(140px); opacity: 0.15; z-index: -1;
        }

        /* --- Navigation --- */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.2rem 8%; backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 1000;
            background: rgba(var(--bg), 0.7);
        }

        .logo { font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .logo i { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .controls { display: flex; align-items: center; gap: 1.2rem; }

        .lang-switch { display: flex; border: 1px solid var(--border); border-radius: 50px; padding: 3px; background: var(--surface); }
        .lang-btn { padding: 5px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); cursor: pointer; }
        .lang-btn.active { background: var(--primary); color: white; }

        .theme-toggle {
            width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center; cursor: pointer; background: var(--surface);
        }

        /* --- Hero --- */
        .hero { padding: 10rem 8% 6rem; text-align: center; position: relative; }
        .badge {
            display: inline-block; padding: 8px 20px; border-radius: 50px;
            background: rgba(99, 102, 241, 0.1); border: 1px solid var(--border);
            color: var(--primary); font-size: 0.85rem; font-weight: 600; margin-bottom: 2rem;
        }
        .hero h1 { font-size: 4.8rem; letter-spacing: -3px; line-height: 1; margin-bottom: 1.5rem; font-weight: 800; }
        .text-gradient { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { max-width: 700px; margin: 0 auto 3rem; color: var(--text-muted); font-size: 1.25rem; }

        .btn {
            padding: 1.1rem 2.8rem; border-radius: 50px; font-weight: 700; text-decoration: none;
            display: inline-block; cursor: pointer; border: none; font-family: 'Outfit';
        }
        .btn-primary { background: var(--gradient); color: white; box-shadow: 0 15px 30px var(--glow); }
        .btn-primary:hover { transform: translateY(-3px); opacity: 0.9; }

        /* --- Sections Layout --- */
        .section-title { text-align: center; margin-bottom: 4rem; }
        .section-title h2 { font-size: 3rem; margin-bottom: 1rem; }
        .section-title p { color: var(--text-muted); font-size: 1.1rem; }

        /* --- Features & Steps --- */
        .container { padding: 6rem 8%; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        
        .glass-card {
            background: var(--surface); border: 1px solid var(--border);
            padding: 3rem; border-radius: 28px; backdrop-filter: blur(10px);
        }
        .glass-card:hover { border-color: var(--primary); transform: translateY(-5px); }
        
        .icon-box { 
            width: 60px; height: 60px; background: var(--gradient); border-radius: 18px; 
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 2rem;
        }

        /* --- How it works --- */
        .step-card { text-align: center; }
        .step-number { font-size: 4rem; font-weight: 800; opacity: 0.1; margin-bottom: -2rem; display: block; }

        /* --- Pricing --- */
        .pricing-card { position: relative; border-radius: 28px; padding: 3rem; background: var(--surface); border: 1px solid var(--border); text-align: center; }
        .pricing-card.featured { border: 2px solid var(--primary); transform: scale(1.05); }
        .price { font-size: 3.5rem; font-weight: 800; margin: 1.5rem 0; }
        .price span { font-size: 1rem; color: var(--text-muted); }
        .pricing-features { list-style: none; margin: 2rem 0; text-align: left; }
        .pricing-features li { margin-bottom: 1rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px; }
        .pricing-features i { color: var(--primary); }

        /* --- Testimonials --- */
        .testimonial-card { font-style: italic; position: relative; }
        .testimonial-card::before { content: '"'; font-size: 5rem; position: absolute; top: -20px; left: 20px; opacity: 0.1; }
        .user-info { display: flex; align-items: center; gap: 15px; margin-top: 2rem; }
        .avatar { width: 50px; height: 50px; border-radius: 50%; background: var(--border); }

        /* --- CTA --- */
        .cta-box {
            background: var(--gradient); color: white; padding: 5rem; border-radius: 40px; text-align: center;
            margin-top: 4rem; box-shadow: 0 20px 50px var(--glow);
        }
        .cta-box h2 { font-size: 3.5rem; margin-bottom: 1.5rem; }
        .btn-white { background: white; color: var(--primary); }

        footer { text-align: center; padding: 6rem 0; border-top: 1px solid var(--border); margin-top: 4rem; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 3rem; }
            .cta-box { padding: 2rem; }
            .pricing-card.featured { transform: scale(1); }
        }
    </style>
</head>
<body>

    <div class="aurora"></div>

    <nav>
        <div class="logo">
            <i class="fas fa-terminal"></i> WorkSpot
        </div>
        
        <div class="controls">
            <div class="lang-switch" id="langSwitch">
                <div class="lang-btn active" data-lang="es">ES</div>
                <div class="lang-btn" data-lang="en">EN</div>
            </div>
            <div class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon" id="themeIcon"></i>
            </div>
            <a href="login.php" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.9rem;">Login</a>
        </div>
    </nav>

    <section class="hero">
        <span class="badge" id="txt-badge">SaaS de nueva generación</span>
        <h1 id="txt-title">Gestión de espacios <br><span class="text-gradient">sin límites.</span></h1>
        <p id="txt-p">WorkSpot redefine la experiencia de coworking. Un backend robusto en PHP con una interfaz diseñada para la máxima productividad.</p>
        <a href="registro.php" class="btn btn-primary" id="txt-btn">Empezar ahora</a>
    </section>

    <section class="container" id="features">
        <div class="grid-3">
            <div class="glass-card">
                <div class="icon-box"><i class="fas fa-microchip"></i></div>
                <h3 id="f1-t">Motor Inteligente</h3>
                <p id="f1-p">Algoritmo de prevención de conflictos en tiempo real que garantiza disponibilidad absoluta.</p>
            </div>
            <div class="glass-card">
                <div class="icon-box"><i class="fas fa-fingerprint"></i></div>
                <h3 id="f2-t">Acceso Seguro</h3>
                <p id="f2-p">Protocolos de seguridad avanzados para la gestión de usuarios y protección de datos sensibles.</p>
            </div>
            <div class="glass-card">
                <div class="icon-box"><i class="fas fa-layer-group"></i></div>
                <h3 id="f3-t">Escalabilidad</h3>
                <p id="f3-p">Diseñado para crecer. Desde un pequeño local hasta una red global de oficinas corporativas.</p>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="section-title">
            <h2 id="hw-t">Cómo funciona</h2>
            <p id="hw-p">Tres pasos simples para optimizar tu jornada laboral.</p>
        </div>
        <div class="grid-3">
            <div class="step-card">
                <span class="step-number">01</span>
                <h3 id="s1-t">Explora</h3>
                <p id="s1-p">Busca espacios disponibles por fecha, tipo o capacidad en tiempo real.</p>
            </div>
            <div class="step-card">
                <span class="step-number">02</span>
                <h3 id="s2-t">Reserva</h3>
                <p id="s2-p">Confirma tu selección con un solo click. Sin esperas ni confirmaciones manuales.</p>
            </div>
            <div class="step-card">
                <span class="step-number">03</span>
                <h3 id="s3-t">Disfruta</h3>
                <p id="s3-p">Accede a tu oficina y enfócate en lo que realmente importa: tu trabajo.</p>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="section-title">
            <h2 id="pr-t">Planes a tu medida</h2>
            <p id="pr-p">Soluciones flexibles para nómadas digitales y grandes empresas.</p>
        </div>
        <div class="grid-3">
            <div class="pricing-card">
                <h3>Flex</h3>
                <div class="price">$0<span>/mes</span></div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> <span id="p1-1">5 Reservas al mes</span></li>
                    <li><i class="fas fa-check"></i> <span id="p1-2">Acceso a zonas comunes</span></li>
                    <li><i class="fas fa-check"></i> <span id="p1-3">WiFi de alta velocidad</span></li>
                </ul>
                <a href="registro.php" class="btn btn-outline" style="border:1px solid var(--border); width:100%">Plan Free</a>
            </div>
            <div class="pricing-card featured">
                <h3>Pro</h3>
                <div class="price">$49<span>/mes</span></div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> <span id="p2-1">Reservas ilimitadas</span></li>
                    <li><i class="fas fa-check"></i> <span id="p2-2">Sala de juntas (10h)</span></li>
                    <li><i class="fas fa-check"></i> <span id="p2-3">Soporte prioritario 24/7</span></li>
                </ul>
                <a href="registro.php" class="btn btn-primary" style="width:100%">Upgrade Pro</a>
            </div>
            <div class="pricing-card">
                <h3>Enterprise</h3>
                <div class="price">$199<span>/mes</span></div>
                <ul class="pricing-features">
                    <li><i class="fas fa-check"></i> <span id="p3-1">Oficinas privadas</span></li>
                    <li><i class="fas fa-check"></i> <span id="p3-2">Acceso biométrico</span></li>
                    <li><i class="fas fa-check"></i> <span id="p3-3">Gestión de múltiples sedes</span></li>
                </ul>
                <a href="registro.php" class="btn btn-outline" style="border:1px solid var(--border); width:100%">Contratar</a>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="cta-box">
            <h2 id="cta-t">¿Listo para elevar <br> tu productividad?</h2>
            <p id="cta-p" style="margin-bottom: 2rem; opacity: 0.9;">Únete a miles de profesionales que ya gestionan sus espacios con WorkSpot.</p>
            <a href="registro.php" class="btn btn-white">Get Started Now</a>
        </div>
    </section>

    <footer>
        <p>© 2025 WorkSpot Technology. Crafted by Tomás.</p>
    </footer>

    <script>
        // --- TEMA CLARO/OSCURO ---
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            const isLight = document.body.classList.contains('light-mode');
            themeIcon.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
            localStorage.setItem('workspot-theme', isLight ? 'light' : 'dark');
        });

        if(localStorage.getItem('workspot-theme') === 'light') {
            document.body.classList.add('light-mode');
            themeIcon.className = 'fas fa-sun';
        }

        // --- TRADUCCIONES EXTENDIDAS ---
        const langBtns = document.querySelectorAll('.lang-btn');
        const content = {
            es: {
                badge: "SaaS de nueva generación",
                title: "Gestión de espacios <br><span class='text-gradient'>sin límites.</span>",
                p: "WorkSpot redefine la experiencia de coworking. Un backend robusto en PHP con una interfaz diseñada para la máxima productividad.",
                btn: "Empezar ahora",
                f1t: "Motor Inteligente", f1p: "Prevención de conflictos en tiempo real garantizada.",
                f2t: "Acceso Seguro", f2p: "Protocolos avanzados para protección de datos sensibles.",
                f3t: "Escalabilidad", f3p: "Diseñado para crecer desde locales a redes globales.",
                hwt: "Cómo funciona", hwp: "Tres pasos simples para optimizar tu jornada.",
                s1t: "Explora", s1p: "Busca espacios disponibles por fecha o capacidad.",
                s2t: "Reserva", s2p: "Confirma tu selección con un solo click.",
                s3t: "Disfruta", s3p: "Enfócate en lo que realmente importa: tu trabajo.",
                prt: "Planes a tu medida", prp: "Soluciones flexibles para cada necesidad.",
                p11: "5 Reservas al mes", p12: "Acceso a zonas comunes", p13: "WiFi de alta velocidad",
                p21: "Reservas ilimitadas", p22: "Sala de juntas (10h)", p23: "Soporte prioritario 24/7",
                p31: "Oficinas privadas", p32: "Acceso biométrico", p33: "Múltiples sedes",
                ctat: "¿Listo para elevar <br> tu productividad?", ctap: "Únete a miles de profesionales ya."
            },
            en: {
                badge: "Next-gen SaaS platform",
                title: "Workspace management <br><span class='text-gradient'>without limits.</span>",
                p: "WorkSpot redefines the coworking experience. A robust PHP backend with a UI designed for maximum productivity.",
                btn: "Start now",
                f1t: "Smart Engine", f1p: "Real-time conflict prevention guaranteed availability.",
                f2t: "Secure Access", f2p: "Advanced protocols for sensitive data protection.",
                f3t: "Scalability", f3p: "Designed to grow from local hubs to global networks.",
                hwt: "How it works", hwp: "Three simple steps to optimize your workday.",
                s1t: "Explore", s1p: "Search available spaces by date or capacity.",
                s2t: "Book", s2p: "Confirm your selection with a single click.",
                s3t: "Enjoy", s3p: "Focus on what really matters: your work.",
                prt: "Tailored Plans", prp: "Flexible solutions for every need.",
                p11: "5 Bookings per month", p12: "Common areas access", p13: "High-speed WiFi",
                p21: "Unlimited bookings", p22: "Meeting room (10h)", p23: "Priority 24/7 support",
                p31: "Private offices", p32: "Biometric access", p33: "Multi-location management",
                ctat: "Ready to elevate <br> your productivity?", ctap: "Join thousands of professionals today."
            }
        };

        langBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const lang = btn.dataset.lang;
                langBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Actualizar todo el texto
                document.getElementById('txt-badge').innerText = content[lang].badge;
                document.getElementById('txt-title').innerHTML = content[lang].title;
                document.getElementById('txt-p').innerText = content[lang].p;
                document.getElementById('txt-btn').innerText = content[lang].btn;
                document.getElementById('f1-t').innerText = content[lang].f1t; document.getElementById('f1-p').innerText = content[lang].f1p;
                document.getElementById('f2-t').innerText = content[lang].f2t; document.getElementById('f2-p').innerText = content[lang].f2p;
                document.getElementById('f3-t').innerText = content[lang].f3t; document.getElementById('f3-p').innerText = content[lang].f3p;
                document.getElementById('hw-t').innerText = content[lang].hwt; document.getElementById('hw-p').innerText = content[lang].hwp;
                document.getElementById('s1-t').innerText = content[lang].s1t; document.getElementById('s1-p').innerText = content[lang].s1p;
                document.getElementById('s2-t').innerText = content[lang].s2t; document.getElementById('s2-p').innerText = content[lang].s2p;
                document.getElementById('s3-t').innerText = content[lang].s3t; document.getElementById('s3-p').innerText = content[lang].s3p;
                document.getElementById('pr-t').innerText = content[lang].prt; document.getElementById('pr-p').innerText = content[lang].prp;
                document.getElementById('p1-1').innerText = content[lang].p11; document.getElementById('p1-2').innerText = content[lang].p12; document.getElementById('p1-3').innerText = content[lang].p13;
                document.getElementById('p2-1').innerText = content[lang].p21; document.getElementById('p2-2').innerText = content[lang].p22; document.getElementById('p2-3').innerText = content[lang].p23;
                document.getElementById('p3-1').innerText = content[lang].p31; document.getElementById('p3-2').innerText = content[lang].p32; document.getElementById('p3-3').innerText = content[lang].p33;
                document.getElementById('cta-t').innerHTML = content[lang].ctat; document.getElementById('cta-p').innerText = content[lang].ctap;
            });
        });
    </script>
</body>
</html>