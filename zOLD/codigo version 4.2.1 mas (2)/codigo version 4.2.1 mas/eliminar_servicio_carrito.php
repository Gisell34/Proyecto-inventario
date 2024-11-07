<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_POST['codigo_producto'])) {
    $codigo_producto = $_POST['codigo_producto'];
    $tipo_producto = $_POST['tipo_producto'];

    if ($tipo_producto == 'producto') {
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            unset($_SESSION['carrito'][$codigo_producto]);
        }
    } elseif ($tipo_producto == 'servicio') {
        if (isset($_SESSION['servicios'][$codigo_producto])) {
            unset($_SESSION['servicios'][$codigo_producto]);
        }
    }

    header("Location: carrito.php");
    exit();
}

?>