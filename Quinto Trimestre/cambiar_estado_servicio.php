<?php
include('conexion.php');

if (isset($_GET['codigo']) && isset($_GET['estado'])) {
    $codigo = $_GET['codigo'];
    $nuevoEstado = ($_GET['estado'] == 'Activo') ? 'Activo' : 'Inactivo';

    $sql = "UPDATE servicios SET Estado = ? WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("si", $nuevoEstado, $codigo);

    if ($stmt->execute()) {
        echo "El estado del servicio se cambió correctamente.";
    } else {
        echo "Error al cambiar el estado: " . $stmt->error;
    }

    $stmt->close();
    $conex->close();

    header("Location: lista-servicios.php");
    exit;
}
?>