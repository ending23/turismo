<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Se define si el rol es cliente o admin

    // Verificar si el usuario es admin y si está autenticado
    if ($_SESSION['role'] !== 'admin') {
        $error = 'No tienes permiso para crear un administrador.';
    } else {
        // Verificar si el nombre de usuario ya existe
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = 'El nombre de usuario ya existe.';
        } else {
            // Crear el usuario con el rol adecuado
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role]);

            // Redirigir al login si se creó correctamente el usuario
            header('Location: login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <h1>Registrar Usuario</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Rol:</label>
            <select name="role" id="role" required>
                <option value="cliente">Cliente</option>
                <option value="admin">Administrador</option>
            </select>

            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
