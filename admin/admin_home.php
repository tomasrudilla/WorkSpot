<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkSpot Admin | Financial Intelligence</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --bg: #030712;
            --sidebar: #0b1120;
            --surface: rgba(30, 41, 59, 0.4);
            --primary: #6366f1;
            --secondary: #0ea5e9;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.06);
            --success: #10b981;
            --danger: #ef4444;
            --glow: rgba(99, 102, 241, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        main { flex: 1; padding: 2rem 3rem; overflow-y: auto; background-image: radial-gradient(circle at 0% 0%, var(--glow), transparent 40%); }

        /* --- UI HEADER --- */
        .header-finance { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; }
        .header-finance h1 { font-family: 'Outfit'; font-size: 2.2rem; }
        .btn-report { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; }

        /* --- STATS GRID --- */
        .finance-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2.5rem; }
        .card-stat { background: var(--surface); padding: 2rem; border-radius: 24px; border: 1px solid var(--border); backdrop-filter: blur(10px); }
        .card-stat p { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; }
        .card-stat h2 { font-family: 'Outfit'; font-size: 2.4rem; margin: 0.5rem 0; }
        .badge-trend { padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; }
        .badge-trend.up { background: rgba(16, 185, 129, 0.1); color: var(--success); }

        /* --- CHARTS SECTION --- */
        .charts-main-grid { display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 1.5rem; margin-bottom: 2.5rem; }
        .chart-wrapper { background: var(--surface); padding: 2rem; border-radius: 28px; border: 1px solid var(--border); height: 400px; display: flex; flex-direction: column; }
        .chart-header { margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .chart-container { position: relative; flex: 1; width: 100%; }

        /* --- TABLES & FEED --- */
        .lower-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
        .content-box { background: var(--surface); border-radius: 28px; border: 1px solid var(--border); overflow: hidden; }
        .box-title { padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); font-family: 'Outfit'; font-size: 1.2rem; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 1rem 2rem; color: var(--text-muted); font-size: 0.8rem; border-bottom: 1px solid var(--border); }
        td { padding: 1.2rem 2rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; }

        .transaction-item { display: flex; align-items: center; gap: 15px; padding: 1.2rem 2rem; border-bottom: 1px solid var(--border); }
        .icon-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; }
    </style>
</head>
<body>

    <?php include '../includes/sidebar_admin.php'; ?>

    <main>
        <div class="header-finance">
            <div>
                <p style="color: var(--primary); font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Balance General</p>
                <h1>Finanzas y Operaciones</h1>
            </div>
            <button class="btn-report">
                <i class="fas fa-file-invoice-dollar"></i> Descargar Reporte Anual
            </button>
        </div>

        <div class="finance-grid">
            <div class="card-stat">
                <p>Ingresos Brutos</p>
                <h2>$42,850.50</h2>
                <span class="badge-trend up"><i class="fas fa-arrow-up"></i> 18.2%</span>
            </div>
            <div class="card-stat">
                <p>Gastos Operativos</p>
                <h2 style="color: var(--danger);">$12,400.00</h2>
                <span class="badge-trend" style="color: var(--text-muted);">Mantenimiento & Staff</span>
            </div>
            <div class="card-stat">
                <p>Utilidad Neta</p>
                <h2 style="color: var(--success);">$30,450.50</h2>
                <span class="badge-trend up"><i class="fas fa-arrow-up"></i> 5.4%</span>
            </div>
            <div class="card-stat">
                <p>Valor por Cliente</p>
                <h2>$104.20</h2>
                <span class="badge-trend up"><i class="fas fa-arrow-up"></i> 2.1%</span>
            </div>
        </div>

        <div class="charts-main-grid">
            <div class="chart-wrapper">
                <div class="chart-header">
                    <h3>Rendimiento Financiero (Ene - Dic)</h3>
                    <select style="background: var(--bg); color: white; border: 1px solid var(--border); padding: 5px; border-radius: 5px;">
                        <option>2025</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="mainFinanceChart"></canvas>
                </div>
            </div>
            <div class="chart-wrapper">
                <div class="chart-header">
                    <h3>Ingresos por Servicio</h3>
                </div>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="lower-grid">
            <div class="content-box">
                <div class="box-title">Últimas Transacciones</div>
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: 600;">Lionel Messi</td>
                            <td>Membresía VIP</td>
                            <td>Stripe / Visa</td>
                            <td style="color: var(--success); font-weight: 700;">+ $500.00</td>
                            <td><span style="color: var(--success);">Completado</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Santi Maratea</td>
                            <td>Escritorio Flex</td>
                            <td>MercadoPago</td>
                            <td style="color: var(--success); font-weight: 700;">+ $25.00</td>
                            <td><span style="color: var(--success);">Completado</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Google Argentina</td>
                            <td>Alquiler Sala Juntas</td>
                            <td>Transferencia</td>
                            <td style="color: var(--success); font-weight: 700;">+ $2,400.00</td>
                            <td><span style="color: var(--warning);">En Proceso</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="content-box">
                <div class="box-title">Gastos Recientes</div>
                <div class="transaction-item">
                    <div class="icon-circle" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);"><i class="fas fa-bolt"></i></div>
                    <div>
                        <p style="font-weight: 600; font-size: 0.85rem;">Edesur - Suministro</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">- $450.00</p>
                    </div>
                </div>
                <div class="transaction-item">
                    <div class="icon-circle" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);"><i class="fas fa-coffee"></i></div>
                    <div>
                        <p style="font-weight: 600; font-size: 0.85rem;">Café Martínez - Insumos</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">- $120.00</p>
                    </div>
                </div>
                <div class="transaction-item">
                    <div class="icon-circle" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);"><i class="fas fa-wifi"></i></div>
                    <div>
                        <p style="font-weight: 600; font-size: 0.85rem;">Fibertel - 1Gbps</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">- $80.00</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // FIX: Gráfico Principal (Revenue vs Expenses)
            const ctxMain = document.getElementById('mainFinanceChart').getContext('2d');
            new Chart(ctxMain, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: [25000, 28000, 32000, 30000, 35000, 38000, 42000, 41000, 45000, 48000, 52000, 55000],
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Gastos',
                            data: [12000, 12500, 13000, 12800, 13500, 14000, 14500, 14200, 15000, 15500, 16000, 16500],
                            borderColor: '#ef4444',
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { color: '#94a3b8', font: { size: 12 } } } },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                    }
                }
            });

            // FIX: Gráfico de Categorías (Doughnut)
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: ['Suscripciones', 'Sala Juntas', 'Escritorio Flex', 'Cafetería'],
                    datasets: [{
                        data: [55, 25, 15, 5],
                        backgroundColor: ['#6366f1', '#0ea5e9', '#f59e0b', '#10b981'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 20 } }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</body>
</html>