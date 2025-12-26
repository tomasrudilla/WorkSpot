<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WorkSpot | Mi Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #030712; color: white; margin: 0; display: flex; }
        main { flex: 1; margin-left: 280px; padding: 3rem 5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .qr-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); padding: 3rem; border-radius: 50px; text-align: center; backdrop-filter: blur(20px); }
        .qr-box { background: white; padding: 1.5rem; border-radius: 30px; margin: 2rem 0; display: inline-block; }
    </style>
</head>
<body>
    <?php include '../includes/sidebar_user.php'; ?>
    <main>
        <div class="qr-card">
            <h1 style="font-family: 'Outfit'; margin: 0;">Pase Digital</h1>
            <p style="color:#94a3b8;">Escaneá este código para ingresar.</p>
            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=User_TG_Elite" alt="QR">
            </div>
            <div style="font-family:'Outfit'; font-size:1.5rem; letter-spacing:2px;">#WS-88229-A</div>
            <div style="margin-top:20px; color:#10b981; font-weight:800;"><i class="fas fa-check-circle"></i> ACCESO ACTIVO</div>
        </div>
    </main>
</body>
</html>