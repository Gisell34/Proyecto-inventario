<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "UPDATE servicios SET Estado = ? WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);

    if (!$stmt) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $estado = 0;
    $stmt->bind_param("is", $estado, $codigo);

    if ($stmt->execute()) {
        echo "Servicio inactivado correctamente.";
    } else {
        echo "Error al inactivar el servicio: " . $stmt->error;
    }
} else {
    echo "Código de servicio no proporcionado.";
}
?>