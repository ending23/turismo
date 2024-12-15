<?php
session_start();
include 'config.php';

// Verificar si el usuario tiene permisos de admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Procesar la solicitud de agregar o editar lugar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $ubicacion = $_POST['ubicacion'];

    if ($id) {
        // Actualizar lugar turístico
        $stmt = $pdo->prepare("UPDATE lugar_turistico SET nombre = ?, descripcion = ?, ubicacion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $ubicacion, $id]);
    } else {
        // Agregar lugar turístico
        $stmt = $pdo->prepare("INSERT INTO lugar_turistico (nombre, descripcion, ubicacion) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $ubicacion]);
    }

    // Redirigir a la página de listado de lugares
    header('Location: lugares.php');
    exit;
}

// Obtener los datos del lugar si estamos editando
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM lugar_turistico WHERE id = ?");
    $stmt->execute([$id]);
    $lugar = $stmt->fetch();
} else {
    $lugar = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lugar ? 'Editar' : 'Agregar'; ?> Lugar Turístico</title>
</head>
<body>
    <h1><?php echo $lugar ? 'Editar' : 'Agregar'; ?> Lugar Turístico</h1>

    <form method="post">
        <?php if ($lugar): ?>
            <input type="hidden" name="id" value="<?php echo $lugar['id']; ?>">
        <?php endif; ?>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $lugar ? htmlspecialchars($lugar['nombre']) : ''; ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $lugar ? htmlspecialchars($lugar['descripcion']) : ''; ?></textarea>

        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" value="<?php echo $lugar ? htmlspecialchars($lugar['ubicacion']) : ''; ?>" required>

        <button type="submit"><?php echo $lugar ? 'Actualizar' : 'Agregar'; ?></button>
    </form>
</body>
</html>
