<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "DELETE FROM producto WHERE Codigoproducto = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("s", $codigo);

    if ($stmt->execute()) {
        echo "Producto eliminado exitosamente.";
    } else {
        echo "Error al eliminar el producto: " . $conex->error;
    }
} else {
    echo "Código de producto no proporcionado.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Producto</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Eliminar Producto</h1>
    <a href="admin.php#productos">Volver a la lista de productos</a>
</body>
</html>