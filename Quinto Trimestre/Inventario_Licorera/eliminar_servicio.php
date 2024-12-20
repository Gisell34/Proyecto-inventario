<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "DELETE FROM servicios WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);

    if (!$stmt) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $stmt->bind_param("s", $codigo);

    if ($stmt->execute()) {
        echo "Servicio eliminado correctamente.";
    } else {
        echo "Error al eliminar el servicio: " . $stmt->error;
    }
} else {
    echo "Código de servicio no proporcionado.";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Eliminar servicio</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <h1>Eliminar servicio</h1>
    <a href="lista-servicios.php">Volver a la lista de servicios</a>
</body>

</html>