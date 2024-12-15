<?php
// gestionar_lugares.php - Código para gestionar lugares turísticos
session_start();
include 'config.php';

// Verificar que el usuario es admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

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
