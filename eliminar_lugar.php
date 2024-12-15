<?php
session_start();
include 'config.php';

// Verificar si el usuario tiene permisos de admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el lugar turístico
    $stmt = $pdo->prepare("DELETE FROM lugar_turistico WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: lugares.php');
    exit;
}
?>
