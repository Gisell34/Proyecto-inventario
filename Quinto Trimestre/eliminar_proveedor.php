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
        $_SESSION['mensaje'] = "Proveedor eliminado exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el proveedor: " . $conex->error;
        $_SESSION['tipo_mensaje'] = "error";
    }

    $stmt->close();
} else {
    $_SESSION['mensaje'] = "Código de proveedor no proporcionado.";
    $_SESSION['tipo_mensaje'] = "error";
}

$conex->close();
header("Location: lista-proveedores.php");
exit();
?>