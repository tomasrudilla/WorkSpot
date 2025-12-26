<?php
require_once '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $tipo = $_POST['tipo_reserva'];
    $duracion = (int)$_POST['duracion'];
    $fecha = date('Y-m-d');
    $horaInicio = date('H:i:s');
    $horaFin = date('H:i:s', strtotime("+$duracion hours"));

    try {
        $pdo->beginTransaction();

        $puestosAReservar = [];
        if ($tipo == 'individual') {
            $puestosAReservar[] = $_POST['puesto_id'];
        } else {
            // Obtener todos los puestos de la isla
            $stmt = $pdo->prepare("SELECT id FROM puestos WHERE isla_id = ? AND estado = 'disponible'");
            $stmt->execute([$_POST['isla_id']]);
            $puestosAReservar = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        if (empty($puestosAReservar)) throw new Exception("No hay puestos disponibles.");

        $costoTotal = 0;
        foreach ($puestosAReservar as $pid) {
            $stmtP = $pdo->prepare("SELECT precio_hora FROM puestos WHERE id = ? FOR UPDATE");
            $stmtP->execute([$pid]);
            $p = $stmtP->fetch();
            $costoTotal += ($p['precio_hora'] * $duracion);
        }

        // Verificar créditos
        $stmtU = $pdo->prepare("SELECT creditos FROM usuarios WHERE id = ? FOR UPDATE");
        $stmtU->execute([$userId]);
        $user = $stmtU->fetch();

        if ($user['creditos'] < $costoTotal) throw new Exception("Créditos insuficientes. Necesitás $costoTotal pts.");

        // Insertar reservas y actualizar puestos
        foreach ($puestosAReservar as $pid) {
            $pdo->prepare("INSERT INTO reservas (usuario_id, puesto_id, fecha_reserva, hora_inicio, hora_fin, total_pago, estado) VALUES (?, ?, ?, ?, ?, ?, 'confirmada')")
                ->execute([$userId, $pid, $fecha, $horaInicio, $horaFin, ($costoTotal / count($puestosAReservar))]);
            
            $pdo->prepare("UPDATE puestos SET estado = 'ocupado' WHERE id = ?")->execute([$pid]);
        }

        $pdo->prepare("UPDATE usuarios SET creditos = creditos - ? WHERE id = ?")->execute([$costoTotal, $userId]);

        $pdo->commit();
        header("Location: my_reserve.php?status=success");

    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: map_reserve.php?error=" . urlencode($e->getMessage()));
    }
}