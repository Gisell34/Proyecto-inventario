<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "DELETE FROM proveedor WHERE Codigoproveedor = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("i", $codigo);

    if ($stmt->execute()) {
        echo "Proveedor eliminado exitosamente.";
    } else {
        echo "Error al eliminar el proveedor: " . $conex->error;
    }

    $stmt->close();
} else {
    echo "Código de proveedor no proporcionado.";
}

$conex->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Proveedor</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Eliminar Proveedor</h1>
    <a href="lista-proveedores.php">Volver a la lista de proveedores</a>
</body>
</html>