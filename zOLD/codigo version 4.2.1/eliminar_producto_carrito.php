<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

if (isset($_POST['codigo_producto'])) {
    $codigo_producto = $_POST['codigo_producto'];
    if (isset($_SESSION['carrito'][$codigo_producto])) {
        unset($_SESSION['carrito'][$codigo_producto]);
    }
    header("Location: carrito.php");
    exit();
} else {
    header("Location: carrito.php");
    exit();
}