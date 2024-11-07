<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoProducto = $_POST['codigo'];

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    if (array_key_exists($codigoProducto, $_SESSION['carrito'])) {
        $_SESSION['carrito'][$codigoProducto] += 1;
    } else {
        $_SESSION['carrito'][$codigoProducto] = 1;
    }

    header("Location: ver_productos.php");
    exit();
}
?>