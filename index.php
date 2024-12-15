<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.html');
    exit;
}

// Código para obtener los lugares y otros elementos de la base de datos.
include 'config.php';

$stmt = $pdo->query("SELECT * FROM lugar_turistico");
$lugares = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lugares Turísticos</title>
</head>
<body>
    <h1>Lugares Turísticos</h1>
    <a href="agregar_editar_lugar.php">Agregar Lugar Turístico</a>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Ubicación</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($lugares as $lugar): ?>
            <tr>
                <td><?php echo htmlspecialchars($lugar['nombre']); ?></td>
                <td><?php echo htmlspecialchars($lugar['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($lugar['ubicacion']); ?></td>
                <td>
                    <a href="agregar_editar_lugar.php?id=<?php echo $lugar['id']; ?>">Editar</a>
                    <a href="eliminar_lugar.php?id=<?php echo $lugar['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este lugar?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
