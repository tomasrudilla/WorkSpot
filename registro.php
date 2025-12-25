<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nombre'], $_POST['email'], $pass_hash]);
    header("Location: login.php");
}
?>
<form method="POST">
    <input type="text" name="nombre" placeholder="Tu nombre" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Registrarse</button>
</form>