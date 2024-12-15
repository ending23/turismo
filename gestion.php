<?php
// Conectar a la base de datos
include 'config.php';

// Si se recibe una acción para eliminar
if (isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el lugar
    $stmt = $pdo->prepare("DELETE FROM grupos WHERE id = ?");
    $stmt->execute([$id]);

    // Redirigir después de eliminar
    header('Location: gestionar_lugares.php');
    exit;
}

// Si se recibe un formulario para agregar o editar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Editar un lugar existente
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE grupos SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
    } else {
        // Agregar un nuevo lugar
        $stmt = $pdo->prepare("INSERT INTO grupos (nombre, descripcion) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
    }

    // Redirigir después de agregar o editar
    header('Location: gestionar_lugares.php');
    exit;
}

// Obtener todos los lugares de la base de datos
$stmt = $pdo->prepare("SELECT * FROM grupos");
$stmt->execute();
$grupos = $stmt->fetchAll();

// Verificar si estamos editando un lugar
$lugar = null;
if (isset($_GET['action']) && $_GET['action'] == 'editar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM grupos WHERE id = ?");
    $stmt->execute([$id]);
    $lugar = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Lugares</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Gestionar Lugares Turísticos</h1>

    <!-- Formulario para agregar o editar un lugar -->
    <h2><?php echo $lugar ? 'Editar Lugar' : 'Agregar Lugar'; ?></h2>
    <form method="post">
        <?php if ($lugar): ?>
            <input type="hidden" name="id" value="<?php echo $lugar['id']; ?>">
        <?php endif; ?>
        <label for="nombre">Nombre del Lugar:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $lugar ? htmlspecialchars($lugar['nombre']) : ''; ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $lugar ? htmlspecialchars($lugar['descripcion']) : ''; ?></textarea>

        <button type="submit"><?php echo $lugar ? 'Actualizar' : 'Agregar'; ?></button>
    </form>

    <h2>Lista de Lugares</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($grupos as $grupo): ?>
            <tr>
                <td><?php echo htmlspecialchars($grupo['nombre']); ?></td>
                <td><?php echo htmlspecialchars($grupo['descripcion']); ?></td>
                <td>
                    <a href="gestionar_lugares.php?action=editar&id=<?php echo $grupo['id']; ?>">Editar</a> |
                    <a href="gestionar_lugares.php?action=eliminar&id=<?php echo $grupo['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este lugar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php">Volver a la página principal</a>
</body>
</html>
